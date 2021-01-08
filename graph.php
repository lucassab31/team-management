<?php
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_pie.php');
require_once ('jpgraph/jpgraph_pie3d.php');

if (isset($_GET['win']) && isset($_GET['lose']) && isset($_GET['draw'])) {
    $win = $_GET['win'];
    $lose = $_GET['lose'];
    $draw = $_GET['draw'];
    $data = array($win, $lose, $draw);
    
    $labels = array("Victoire\n(%.1f%%)", "Défaites\n(%.1f%%)", "Matchs nuls\n(%.1f%%)");

    // Création du camambert
    $graph = new PieGraph(300, 175);
    
    $theme_class= new VividTheme;
    $graph->SetTheme($theme_class);

    
    // Création du graph
    $p1 = new PiePlot3D($data);
    $graph->Add($p1);
    $p1->ShowBorder();
    $p1->SetColor('black');
    $p1->SetLabels($labels);
    $p1->SetLabelPos(1);
    
    $p1->SetLabelType(PIE_VALUE_PER);
    $p1->value->Show();
    $p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
    $p1->value->SetColor('darkgray');
    
    $p1->SetSliceColors(array('forestgreen','red','gray'));
    $graph->Stroke();
} else {
    phpinfo();
}

?>