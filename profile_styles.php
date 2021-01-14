<?php
header("Content-type: text/css");

require_once('query.php');
require_once('database_info.php');
if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8')))
    header("Location: error_page.php");

$ranks = query($conn, "SELECT * from rankingBasic natural join (rankingAdvanced r left join formuly f on r.id_formuly = f.id) where nick_gracza = '". $_COOKIE['active_username'] ."' order by gra");
oci_close($conn);

$imgs = array(
    "straws.jpg",
    "https://s2.best-wallpaper.net/wallpaper/iphone/1911/Red-and-blue-pawn_iphone_1080x1920.jpg",
    "https://images.wallpaperscraft.com/image/soccer_ball_nike_grass_113053_1080x1920.jpg",
    "https://mfiles.alphacoders.com/166/166285.jpg",
    "https://www.xmple.com/wallpaper/checkered-black-white-squares-1080x1920-c2-fff5ee-000000-l-60-a-70-f-2.svg"
);
?>

.ranking {
color: gold;
text-shadow: 3px 3px rgb(139, 62, 10);
white-space: nowrap;
font-size: 400%;
margin-top:5px;
margin-bottom: 5px;
transition: width 0.4s ease-in-out;
width: 25vw;
height: 10vh;
border-radius: 15px;
overflow: hidden;
text-align: center;
}

.ranking:hover {
width: 50vw;
border-radius: 5px;
}

<?php for ($i = 0; $i < $ranks[1]; $i++) {
    echo '#rank' . ($i + 1) . ' {
        background-image: url("' . $imgs[$i] . '");
        background-position: center;
        background-blend-mode: saturation;
        border-radius: 15px;
    }';
    echo '#rank' . ($i + 1) . ':before {
        content:\'' . strtoupper($ranks[0]['GRA'][$i]) . '\';
    }';
    echo '#rank' . ($i + 1) . ':hover:before {
        content:\'ELO : '.$ranks[0]['PKT_RANKINGOWE'][$i].' | Z: '.$ranks[0]['ILOSC_ZAGRANYCH'][$i].' | W: '.$ranks[0]['ILOSC_WYGRANYCH'][$i].
        ' | R: '.$ranks[0]['ILOSC_REMISOW'][$i].'\';
    }';
}
?>

