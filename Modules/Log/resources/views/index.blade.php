<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Log Viewer</title>
    <style>
        body { font-family: monospace; background: #111; color: #0f0; padding: 20px; }
        #log { white-space: pre-wrap; max-height: 80vh; overflow-y: scroll; }
    </style>
</head>
<body>
    <h2>Laravel Log Viewer (Realtime)</h2>
    <div id="log"></div>

    <script>
        const logDiv = document.getElementById('log');
        const evtSource = new EventSource("/log-stream");

        evtSource.onmessage = function (e) {
            logDiv.textContent += e.data + "\n";
            logDiv.scrollTop = logDiv.scrollHeight;
        };
    </script>
</body>
</html>