<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TaxiiService;
use App\Models\Ioc;
use App\Models\User; // Pour l'utilisateur/tenant
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GetThreatIntel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'threat-intel:get {--days=7 : Number of days to look back for IOCs} {--tenant_id= : The ID of the tenant to associate IOCs with (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve threat intelligence from a TAXII server and store IOCs.';

    protected $taxiiService;

    public function __construct(TaxiiService $taxiiService)
    {
        parent::__construct();
        $this->taxiiService = $taxiiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $tenantId = $this->option('tenant_id');

        // Si aucun tenant_id n'est spécifié, nous devons en choisir un par défaut ou générer une erreur.
        // Pour ce PoC, nous allons utiliser le premier utilisateur trouvé ou spécifié.
        if (!$tenantId) {
            $user = User::first(); // Récupère le premier utilisateur
            if ($user) {
                $tenantId = $user->id;
                $this->info("Aucun tenant_id spécifié. Utilisation de l'utilisateur #{$tenantId} comme tenant par défaut.");
            } else {
                $this->error("Aucun utilisateur trouvé et aucun tenant_id spécifié. Veuillez créer un utilisateur ou spécifier un tenant_id.");
                return Command::FAILURE;
            }
        } else {
            $user = User::find($tenantId);
            if (!$user) {
                $this->error("Le tenant_id #{$tenantId} n'existe pas.");
                return Command::FAILURE;
            }
        }

        $collectionId = env('TAXII_COLLECTION_ID');
        if (!$collectionId) {
            $this->error("Variable d'environnement TAXII_COLLECTION_ID non définie.");
            return Command::FAILURE;
        }

        $this->info("Récupération des IOCs du serveur TAXII pour la collection : {$collectionId} (sur les {$days} derniers jours) pour le tenant #{$tenantId}...");

        $stixObjects = $this->taxiiService->getThreatIntelligence($collectionId, $days);

        if (empty($stixObjects)) {
            $this->info("Aucun objet STIX récupéré.");
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($stixObjects as $stixObject) {
            // IMPORTANT: C'est ici que vous devrez analyser l'objet STIX
            // et extraire les informations pertinentes pour votre modèle IOC.
            // Le format STIX peut varier (STIX 2.0, 2.1, 1.x), donc l'analyse dépendra de cela.

            // Ceci est un exemple simplifié pour STIX 2.x 'indicator' ou 'observed-data'
            // Vous devrez adapter cette logique à la structure exacte des STIX Objects que vous recevez.

            if (isset($stixObject['type']) && $stixObject['type'] === 'indicator') {
                $value = $stixObject['pattern'] ?? 'N/A'; // Le pattern STIX contient l'IOC
                $type = $stixObject['pattern_type'] ?? 'unknown';
                $description = $stixObject['description'] ?? null;
                $source = env('TAXII_SERVER_URL'); // Ou le nom du flux
                $detectedAt = isset($stixObject['valid_from']) ? Carbon::parse($stixObject['valid_from']) : Carbon::now();

                // Nettoyer la valeur pour n'extraire que l'IOC réel (si le pattern est "['file:hashes.MD5_hash = 'abcdef...']" )
                // Ceci est un exemple TRÈS BASIQUE, vous aurez besoin d'un parsing plus robuste pour STIX patterns.
                if (str_contains($value, ':')) {
                    $parts = explode('=', $value);
                    if (isset($parts[1])) {
                         $value = trim($parts[1], " '[]"); // Enlève les espaces, quotes, crochets
                    }
                }

                // Vérifiez si l'IOC existe déjà pour ce tenant
                $existingIoc = Ioc::where('tenant_id', $tenantId)
                                  ->where('value', $value)
                                  ->where('type', $type)
                                  ->first();

                if (!$existingIoc) {
                    Ioc::create([
                        'tenant_id' => $tenantId,
                        'value' => $value,
                        'type' => $type,
                        'description' => $description,
                        'source' => $source,
                        'detected_at' => $detectedAt,
                        'stix_data' => $stixObject, // Stocke l'objet STIX brut pour référence
                    ]);
                    $count++;
                    $this->info("IOC ajouté: {$type}: {$value}");
                } else {
                    // Vous pouvez choisir de mettre à jour l'IOC existant ici
                    $this->line("IOC existant ignoré: {$type}: {$value}");
                }
            } elseif (isset($stixObject['type']) && $stixObject['type'] === 'observed-data') {
                // Les observed-data sont plus complexes, ils contiennent des 'objects' internes
                // qui décrivent des observables comme des adresses IP, des fichiers, etc.
                // Il faut itérer sur les 'objects' pour trouver les IOCs.
                if (isset($stixObject['objects'])) {
                    foreach ($stixObject['objects'] as $obj_key => $stixObservedObject) {
                        $observableType = $stixObservedObject['type'] ?? 'unknown';
                        $value = 'N/A';

                        switch ($observableType) {
                            case 'ipv4-addr':
                                $value = $stixObservedObject['value'] ?? 'N/A';
                                break;
                            case 'domain-name':
                                $value = $stixObservedObject['value'] ?? 'N/A';
                                break;
                            case 'file':
                                if (isset($stixObservedObject['hashes']['MD5'])) {
                                    $value = $stixObservedObject['hashes']['MD5'];
                                    $observableType = 'file-hash-md5';
                                } elseif (isset($stixObservedObject['hashes']['SHA-256'])) {
                                    $value = $stixObservedObject['hashes']['SHA-256'];
                                    $observableType = 'file-hash-sha256';
                                }
                                break;
                            // Ajoutez d'autres types d'observables STIX si nécessaire
                        }

                        if ($value !== 'N/A' && $value !== null) {
                            $existingIoc = Ioc::where('tenant_id', $tenantId)
                                              ->where('value', $value)
                                              ->where('type', $observableType)
                                              ->first();

                            if (!$existingIoc) {
                                Ioc::create([
                                    'tenant_id' => $tenantId,
                                    'value' => $value,
                                    'type' => $observableType,
                                    'description' => $stixObject['description'] ?? null,
                                    'source' => env('TAXII_SERVER_URL'),
                                    'detected_at' => isset($stixObject['first_observed']) ? Carbon::parse($stixObject['first_observed']) : Carbon::now(),
                                    'stix_data' => $stixObject,
                                ]);
                                $count++;
                                $this->info("IOC (Observed Data) ajouté: {$observableType}: {$value}");
                            } else {
                                $this->line("IOC (Observed Data) existant ignoré: {$observableType}: {$value}");
                            }
                        }
                    }
                }
            } else {
                $this->line("Objet STIX de type inconnu ou non traité: {$stixObject['type']}");
            }
        }

        $this->info("Processus de récupération et de stockage des IOCs terminé. {$count} nouveaux IOCs ajoutés.");
        return Command::SUCCESS;
    }
}