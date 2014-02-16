
<!DOCTYPE HTML>
<html>
<head>
    <title>Mango framework</title>
    <meta charset="utf-8">
    <meta name="description" content="A framework to build REST APIs">
    <meta name="keywords" content="framework, API, REST">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat+Alternates:400,700' rel='stylesheet' type='text/css'>
    <link rel="icon" href="./asset/favicon.ico" type="image/x-icon"> <link rel="icon" href="./asset/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./asset/css/reset.css">
    <link rel="stylesheet" href="./asset/css/style.css">
</head>
<body>
<header id="headerConsole">
    <h1>
        <img src="./asset/img/logoW.png" alt="Mango Framework">
        <span>Mango Framework</span>
    </h1>
</header>
<div id="reqURL">
    <span>Request URL</span>
    <div>
        <select id="methods">
            <option>GET</option>
            <option>POST</option>
            <option>DELETE</option>
            <option>PUT</option>
        </select>
        <input type="text" id="url" placeholder="<- Séléctionnez une méthode API">
        <input type="submit" id="send" value="Envoyer">
    </div>
</div>
<div id="params">
    <table id="paramBody">
        <tr>
            <th>&nbsp;</th>
            <th>Paramètre</th>
            <th>Valeur</th>
        </tr>
        <tr class="trParams">
            <td>
                <span class="ic plus">+</span>
            </td>
            <td>
                <input type="text" placeholder="Nom" class="keyParam0">
            </td>
            <td>
                <input type="text" placeholder="Valeur" class="valueParam0">
            </td>
        </tr>
    </table>
</div>
<div id="params">
    <h1>R&eacute;ponse</h1>

    <table id="paramBody" class="responseBody">

    </table>
</div>
<footer>
    <div class="center">
        Mango Framework - Concepteurs/Développeurs : <a href="http://fr.linkedin.com/in/benoitciret" target="_blank">Benoit Ciret</a>, <a href="http://fr.linkedin.com/in/ismaeltifous" target="_blank">Ismaël Tifous</a>, <a href="http://fr.linkedin.com/pub/nicolas-portier/5b/625/1a1" target="_blank">Nicolas Portier</a> - Designer/Intégrateur : <a href="http://fr.linkedin.com/in/baptistegios/" target="_blank">Baptiste Gios</a>
    </div>
</footer>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type='text/javascript' src="./asset/js/xmlToJson.js"></script>
<script type='text/javascript' src="./asset/js/RESTwebservice.js"></script>
</body>
</html>