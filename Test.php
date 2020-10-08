<?php
    echo "<style>.rainbowBoard{border:1px solid #ccc;margin-top:10px}.rainbowBoard td{padding:8px 15px 8px 15px}.rainbowBoard tr>td:nth-child(1){color:#fff;width:50%}.rainbowBoard tr>td:nth-child(2){color:#fff}.
    black{color:#000!important}.partClosure,.severeDelays{background:#faf5e1}.
    .londonoverground{background:#e86a10}.london-overground{background:#e86a10}.piccadillyline{background:#0019a8}.bakerlooline{background:#894e24}.centralline{background:#dc241f}.circleline{background:#ffce00}
    .districtline{background:#007229}.hammersmithcityline{background:#d799af}.hammersmith-city{background:#d799af}.jubileeline{background:#6a7278}.metropolitanline{background:#751056}.northernline{background:#000}
    .victorialine{background:#00a0e2}.waterloocityline{background:#76d0bd}.tfLRail{background:#0019a8}.docklandslightrailway{background:#00afad}.londontrams{background:#6c0}</style>";
    $tflData = json_decode(file_get_contents("https://api.tfl.gov.uk/line/mode/tube,overground,dlr,tflrail,tram/status?"));
    $arrSavedNames = [];
    $arrResult = [];
    foreach ($tflData as $tflDatum) {
        $strLineID = strtr($tflDatum->id, ["-" => ""]);
        $strMode = $tflDatum->modeName;
        if ($strMode == "tube") {
            $strMode = "line";
        } else if ($strMode == "overground") {
            $strMode = "";
        } else if ($strMode == "dlr") {
            $strMode = "";
            $strLineID = "docklandslightrailway";
        } else if ($strMode == "tflrail") {
            $strMode = "";
            $strLineID = "tflrail";
        } else if ($strMode == "tram") {
            $strLineID = "londontrams";
            $strMode = "";
        }
        $strLineID = $strLineID;
        if (isset($tflDatum->name)) {
            $arrSavedNames[$strLineID . $strMode] = $tflDatum->name;
        }
        foreach ($tflDatum->lineStatuses as $lineStatus) {
            if (!isset($arrResult[$strLineID . $strMode])) {
                $arrResult[$strLineID . $strMode] = $lineStatus->statusSeverityDescription;
            } else {
                {
                    $arrResult[$strLineID . $strMode] .= ", " . $lineStatus->statusSeverityDescription;
                }
            }
        }
    }
    $strStandardRainbowBoard = "";
    foreach ($arrResult as $strID => $strStatus) {
        $strStandardRainbowBoard .= PHP_EOL . "<tr><td class='$strID'>" . $arrSavedNames[$strID] . "</td><td class='$strID'>$strStatus</td></tr>";
    }
    echo "<table class='rainbowBoard'>" . $strStandardRainbowBoard . "</table>";