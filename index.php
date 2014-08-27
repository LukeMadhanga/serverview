<?php

echo <<<HTML
<html>
    <head>
        <script type='text/javascript' src='//code.jquery.com/jquery-latest.min.js'></script>
        <script type='text/javascript' src='fillview.min.js'></script>
        <script type='text/javascript' src='serverview.js'></script>
        <link href='http://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400|Open+Sans:400,300' rel='stylesheet' type='text/css'>
        <script>
            $(function () {
                $('#contents').serverView({path:'getFiles.php', maxFiles: 1000, startAt: 30, position: true, randomize: true});
            });
        </script>
        <style>
            body {margin: 0;}
            * {font-family: 'Josefin Sans',sans-serif;}
            #title {max-width:500px;text-align: center;margin: auto;font-size: 30px;border-bottom: solid thin #444;padding: 10px 0;}
            #par {max-width: 600px;text-align: center;margin: auto;}
            .containers {padding: 20px 0;margin: auto;}
            #contents {width: 1000px;margin: 20px auto;}
            .imgcontainer {width: 200px;height: 200px;overflow: hidden;display: inline-block;}
            img {-webkit-transition: -webkit-filter 0.8s;-webkit-filter: grayscale(100%);}
            img:hover {-webkit-filter: grayscale(0%);}
        </style>
    </head>
    <body>
        <div id='title'>SERVER<strong>VIEW</strong></div>
        <div id='contents'></div>
    </body>
</html>
HTML;
