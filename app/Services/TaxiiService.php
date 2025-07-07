<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaxiiService
{
    protected $client;
    protected $taxiiUrl;
    protected $username;
    protected $password;
    protected $verifySsl;

    public function __construct()
    {
        $this->taxiiUrl = env('TAXII_SERVER_URL');
        $this->username = env('TAXII_USERNAME');
        $this->password = env('TAXII_PASSWORD');
        $this->verifySsl = env('TAXII_VERIFY_SSL', true);

        $this->client = new Client([
            'base_uri' => $this->taxiiUrl,
            'verify' => $this->verifySsl,
            'auth' => $this->username && $this->password ? [$this->username, $this->password] : null,
        ]);
    }

    /**
     * Récupère les objets STIX d'une collection TAXII 2.x.
     *
     * @param string $collectionId L'ID de la collection TAXII.
     * @param int $daysBack Le nombre de jours à remonter pour la récupération.
     * @return array Les objets STIX récupérés.
     */
    public function getThreatIntelligence(string $collectionId, int $daysBack = 7): array
    {
        try {
            $endDate = Carbon::now()->toIso8601ZuluString();
            $beginDate = Carbon::now()->subDays($daysBack)->toIso8601ZuluString();

            Log::info("Interrogation TAXII pour collection {$collectionId} de {$beginDate} à {$endDate}");

            $response = $this->client->get("{$collectionId}/objects", [
                'query' => [
                    'added_after' => $beginDate,
                    'match' => 'all', // Pour STIX 2.x, d'autres filtres peuvent exister
                ],
                'headers' => [
                    'Accept' => 'application/taxii+json;version=2.1', // Assurez-vous de la version correcte (2.0 ou 2.1)
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erreur de décodage JSON de la réponse TAXII: ' . json_last_error_msg());
            }

            return $data['objects'] ?? [];

        } catch (RequestException $e) {
            Log::error("Erreur Request TAXII: " . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error("Réponse TAXII: " . $e->getResponse()->getBody()->getContents());
            }
            return [];
        } catch (\Exception $e) {
            Log::error("Erreur générale TAXII: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Découvre les collections disponibles sur le serveur TAXII (pour debugging/info).
     * Peut varier selon l'implémentation du serveur TAXII (souvent /taxii2/collections/)
     */
    public function discoverCollections(): array
    {
        try {
            // Adaptez l'URL si votre serveur TAXII n'expose pas directement /taxii2/collections
            // Pour Anomali Limo, l'URL est /api/v1/taxii2/feeds/
            // Vous devrez peut-être ajuster base_uri du client si vous utilisez un endpoint spécifique
            $response = $this->client->get("collections", [
                'headers' => [
                    'Accept' => 'application/taxii+json;version=2.1',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['collections'] ?? [];
        } catch (\Exception $e) {
            Log::error("Erreur lors de la découverte des collections TAXII: " . $e->getMessage());
            return [];
        }
    }
}