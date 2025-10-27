<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babarrun lista</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #4a4a4a;
            border-bottom: 2px solid #005a9c;
            padding-bottom: 5px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        #list-container {
            flex: 1;
            min-width: 250px;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        #editor-container {
            flex: 2;
            min-width: 300px;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        #babarrun-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 400px;
            overflow-y: auto;
        }
        #babarrun-list li {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        #babarrun-list li:hover {
            background-color: #f0f8ff;
        }
        #babarrun-list li.selected {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .form-field {
            margin-bottom: 15px;
        }
        .form-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-field input[type="text"],
        .form-field input[type="number"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-field input[readonly] {
            background-color: #eee;
            cursor: not-allowed;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: #0056b3;
        }
        #message-area {
            margin-top: 15px;
            font-weight: bold;
        }
        .error { color: #dc3545; }
        .success { color: #28a745; }
    </style>
</head>
<body>

    <h1>Babarrunak</h1>

    <div class="container">
        
        <div id="list-container">
            <h2>Babarrun Zerrenda</h2>
            <ul id="babarrun-list">
                </ul>
        </div>

        <div id="editor-container">
            <h2>Editatu Babarruna</h2>
            <form id="editor-form" style="display: none;">
                
                <div class="form-field">
                    <label for="field-id">ID:</label>
                    <input type="text" id="field-id" name="id" readonly>
                </div>
                
                <div class="form-field">
                    <label for="field-izena">Izena:</label>
                    <input type="text" id="field-izena" name="Izena" required>
                </div>

                <div class="form-field">
                    <label for="field-jatorria">Jatorria:</label>
                    <input type="text" id="field-jatorria" name="Jatorria">
                </div>

                <div class="form-field">
                    <label for="field-kolorea">Kolorea:</label>
                    <input type="text" id="field-kolorea" name="Kolorea">
                </div>

                <div class="form-field">
                    <label for="field-denbora">Egozketa denb (min):</label>
                    <input type="number" id="field-denbora" name="Egozketa_denb_min">
                </div>

                <button type="submit">Gorde Aldaketak</button>
                <div id="message-area"></div>

            </form>
            <p id="editor-placeholder">Sakatu zerrendako babarrun bat bere datuak ikusi eta aldatzeko.</p>
        </div>

    </div>

    <script>
        // --- 1. DATUEN BILTEGIA (GLOBAL) ---
        // PHP-tik jasotako datuak hemen gordeko ditugu
        let babarrunakDataStore = [];

        // --- 2. HTML ELEMENTUEN ERREFERENTZIAK ---
        const listElement = document.getElementById('babarrun-list');
        const formElement = document.getElementById('editor-form');
        const editorPlaceholder = document.getElementById('editor-placeholder');
        const messageArea = document.getElementById('message-area');

        // Formularioaren eremuak
        const fieldId = document.getElementById('field-id');
        const fieldIzena = document.getElementById('field-izena');
        const fieldJatorria = document.getElementById('field-jatorria');
        const fieldKolorea = document.getElementById('field-kolorea');
        const fieldDenbora = document.getElementById('field-denbora');

        // --- 3. FUNTZIO NAGUSIAK ---

        /**
         * Datu basea 'api.php'-ri eskatu eta zerrenda HTML-an kargatzen du.
         */
        async function loadBabarrunakList() {
            try {
                // Egin GET eskaera bat gure api.php-ri
                const response = await fetch('api.php');
                if (!response.ok) {
                    throw new Error('Errorea datuak eskuratzean: ' + response.statusText);
                }
                const data = await response.json();

                // Gorde datuak gure biltegi globalean
                babarrunakDataStore = data;

                // Garbitu aurreko zerrenda
                listElement.innerHTML = '';
                
                if (data.length === 0) {
                    listElement.innerHTML = '<li>Ez dago babarrunik datu basean. Gehitu batzuk phpMyAdmin-en.</li>';
                    return;
                }

                // Sortu 'li' elementuak
                data.forEach(babarrun => {
                    const li = document.createElement('li');
                    li.textContent = babarrun.Izena;
                    li.dataset.id = babarrun.id;
                    
                    li.addEventListener('click', () => {
                        displayDetails(parseInt(babarrun.id));
                        
                        document.querySelectorAll('#babarrun-list li').forEach(item => {
                            item.classList.remove('selected');
                        });
                        li.classList.add('selected');
                    });
                    
                    listElement.appendChild(li);
                });

            } catch (error) {
                listElement.innerHTML = `<li>Errorea zerrenda kargatzean: ${error.message}</li>`;
            }
        }

        /**
         * Biltegi lokaletik, babarrun baten datuak formularioan bistaratzen ditu.
         */
        function displayDetails(id) {
            // Aurkitu babarruna gure datu-biltegi lokalean
            const babarrun = babarrunakDataStore.find(b => b.id === id);
            
            if (babarrun) {
                formElement.style.display = 'block';
                editorPlaceholder.style.display = 'none';
                showMessage('', 'success'); // Garbitu mezuak

                fieldId.value = babarrun.id;
                fieldIzena.value = babarrun.Izena;
                fieldJatorria.value = babarrun.Jatorria || '';
                fieldKolorea.value = babarrun.Kolorea || '';
                fieldDenbora.value = babarrun.Egozketa_denb_min || '';
            }
        }

        /**
         * Formularioa bidaltzean, datuak 'api.php'-ra bidaltzen ditu (POST).
         */
        async function saveChanges(event) {
            event.preventDefault(); 
            
            // Lortu formularioaren balioak
            const dataToSave = {
                id: parseInt(fieldId.value),
                Izena: fieldIzena.value,
                Jatorria: fieldJatorria.value,
                Kolorea: fieldKolorea.value,
                Egozketa_denb_min: parseInt(fieldDenbora.value) || null
            };

            try {
                // Egin POST eskaera bat api.php-ri, datuak JSON gisa bidaliz
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSave)
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(`"${dataToSave.Izena}" ondo gorde da!`, 'success');
                    
                    // Zerrenda birkargatu, izena aldatu bada ere
                    await loadBabarrunakList(); 

                    // Mantendu elementua hautatuta
                    const selectedLi = listElement.querySelector(`li[data-id="${dataToSave.id}"]`);
                    if (selectedLi) {
                        selectedLi.classList.add('selected');
                    }
                } else {
                    throw new Error(result.error || 'Errore ezezaguna gordetzean');
                }

            } catch (error) {
                showMessage(`Errorea gordetzean: ${error.message}`, 'error');
            }
        }

        /**
         * Erabiltzaileari mezuak erakusteko laguntzailea.
         */
        function showMessage(message, type) {
            messageArea.textContent = message;
            messageArea.className = type; // 'success' or 'error'

            // Ezkutatu mezua 3 segundo pasa ondoren (erroreak ezik)
            if (type === 'success') {
                setTimeout(() => {
                    messageArea.textContent = '';
                }, 3000);
            }
        }

        // --- 4. HASIERAKO EXEKUZIOA ---

        document.addEventListener('DOMContentLoaded', () => {
            loadBabarrunakList();
            formElement.addEventListener('submit', saveChanges);
        });

    </script>

</body>
</html>