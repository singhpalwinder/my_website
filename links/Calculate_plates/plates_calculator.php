<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href= "styling.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Conversion & Denomination</title>
</head>
<body>
    <form action="plates_calculator.php" method="post">
        Enter weight in lbs: <input type="number" step="0.01" name="weight_in_lbs">
        <input type="submit" value="Calculate">
    </form>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['weight_in_lbs'])) {
        function platesNeeded($target_weight_lbs) {
            $lb_to_kg = 0.453592;
            $target_weight_kg = $target_weight_lbs * $lb_to_kg;
            
            echo "<h3>Given Weight(lbs): " . number_format($target_weight_lbs, 2) . "lbs</h3>";
            echo "<h3>Target weight in kg: " . number_format($target_weight_kg, 2) . "kg</h3>";

            $weight_without_barbell = $target_weight_kg - 20;

            $plates = [25, 20, 15, 10, 5, 2.5, 1.25];
            rsort($plates);

            $plates_needed = [];

            foreach($plates as $plate) {
                if ($weight_without_barbell >= 2 * $plate) {
                    $num_plates = floor($weight_without_barbell / (2 * $plate));
                    $plates_needed[strval($plate)] = $num_plates;
                    $weight_without_barbell -= $num_plates * 2 * $plate;
                }
            }

            return $plates_needed;
        }

        $weight_in_lbs = floatval($_POST['weight_in_lbs']);
        $result = platesNeeded($weight_in_lbs);
        
        echo "<h4>Plates needed on each side:</h4>";
        echo "<ul>";
        foreach($result as $plate => $count) {
            echo "<li>" . floatval($plate) . " kg plates: $count</li>";
        }
        
        echo "</ul>";
    }

    ?>
</body>
</html>
