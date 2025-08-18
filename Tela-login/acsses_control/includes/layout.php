<?php
function template_header($titulo = "Sistema") {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>$titulo</title>
    </head>
    <body>";
}

function template_footer() {
    echo "
    </body>
    </html>";
}
