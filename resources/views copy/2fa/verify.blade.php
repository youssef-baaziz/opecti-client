<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Two-Factor Authentication</title>
        <style>
            body {
                background-color: #FDFDFC;
                color: #1b1b18;
                display: flex;
                padding: 1.5rem;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                flex-direction: column;
                transition: background-color 0.3s ease;
            }
            body.dark {
                background-color: #0a0a0a;
            }
            .form-container {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                transition: opacity 0.75s ease;
                opacity: 1;
            }
            .form-container.starting {
                opacity: 0;
            }
            .form-wrapper {
                display: flex;
                max-width: 335px;
                width: 100%;
                flex-direction: column-reverse;
                margin: 0 auto;
            }
            .form-wrapper.lg {
                max-width: 1024px;
                flex-direction: row;
            }
            form {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }
            label {
                font-weight: bold;
            }
            input[type="text"] {
                padding: 0.5rem;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            button {
                padding: 0.5rem 1rem;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            button:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <div class="form-container">
            <main class="form-wrapper">
                <form method="POST" action="{{ route('2fa.verify') }}">
                    @csrf
                    <label for="code">Verification Code</label>
                    <input type="text" id="code" name="code" required>
                    <button type="submit">Verify</button>
                </form>
            </main>
        </div>
    </body>
</html>
