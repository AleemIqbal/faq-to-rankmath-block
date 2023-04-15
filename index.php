<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Converter</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            padding: 2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">FAQ Converter</h1>
        <form id="converterForm">
            <div class="mb-3">
                <label for="inputText" class="form-label">Enter your FAQ text:</label>
                <textarea class="form-control" id="inputText" name="inputText" rows="10" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Convert</button>
        </form>
        <div id="outputSection" class="mt-4" style="display:none;">
            <h2 class="mb-3">Converted Text</h2>
            <pre id="outputText"></pre>
            <button id="copyButton" class="btn btn-secondary mt-2">Copy to Clipboard</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        document.getElementById('converterForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const inputText = document.getElementById('inputText').value;
            fetch('converter.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `inputText=${encodeURIComponent(inputText)}`
            })
            .then(response => response.text())
            .then(outputText => {
                document.getElementById('outputText').innerText = outputText;
                document.getElementById('outputSection').style.display = 'block';
            });
        });

        document.getElementById('copyButton').addEventListener('click', function() {
            const textarea = document.createElement('textarea');
            textarea.value = document.getElementById('outputText').innerText;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        });
    </script>
</body>

</html>