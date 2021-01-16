<?php
header("Content-type: text/css");

require_once('query.php');
require_once('database_info.php');
if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) {
    header("Location: error_page.php");
    exit;
}

$ranks = query($conn, "SELECT gra, pkt_rankingowe, ilosc_zagranych, ilosc_wygranych, ilosc_przegranych, f.nazwa as nzw from rankingBasic natural join (rankingAdvanced r left join (sposobyObliczania s left join formuly f on f.id = s.id_formuly) on r.id_sposobu = s.id) where nick_gracza = '" . $_COOKIE['active_username'] . "' and id_formuly=0 order by gra");
oci_close($conn);

$imgs = array(
    "straws.jpg",
    "https://s2.best-wallpaper.net/wallpaper/iphone/1911/Red-and-blue-pawn_iphone_1080x1920.jpg",
    "https://images.wallpaperscraft.com/image/soccer_ball_nike_grass_113053_1080x1920.jpg",
    "https://mfiles.alphacoders.com/166/166285.jpg",
    "https://i.pinimg.com/originals/e9/72/9a/e9729ae1740af32fe8ba141d6b78ed51.jpg"
);
?>

.ranking {
display: inline-block;
color: gold;
text-shadow: 3px 3px rgb(139, 62, 10);
white-space: nowrap;
margin-top:5px;
margin-bottom: 5px;
transition: width 0.4s ease;
width: 25vw;
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
        height: 8vh;
        font-size: 3vw;
        line-height: 8vh;
    }';
    echo '#rank' . ($i + 1) . ':before {
        content:\'' . strtoupper($ranks[0]['GRA'][$i]) . '\';
    }';
    echo '#rank' . ($i + 1) . ':hover:before {
        content:\''.strtoupper($ranks[0]['NZW'][$i]).' : '.$ranks[0]['PKT_RANKINGOWE'][$i].' | Z: '.$ranks[0]['ILOSC_ZAGRANYCH'][$i].' | W: '.$ranks[0]['ILOSC_WYGRANYCH'][$i].
        ' | P: '.$ranks[0]['ILOSC_PRZEGRANYCH'][$i].'\';
    }';
}
?>

