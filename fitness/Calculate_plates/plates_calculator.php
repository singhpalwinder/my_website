<?php
// Start the session at the top
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['weight_in_lbs'])) {
    function platesNeeded($target_weight_lbs) {
        $lb_to_kg = 0.453592;
        $target_weight_kg = $target_weight_lbs * $lb_to_kg;

        $results = [
            'weight_lbs' => number_format($target_weight_lbs, 2),
            'weight_kg' => number_format($target_weight_kg, 2)
        ];

        $weight_without_barbell = $target_weight_kg - 20;
        $plates = [25, 20, 15, 10, 5, 2.5, 1.25];
        rsort($plates);

        $plates_needed = [];
        foreach($plates as $plate) {
            while ($weight_without_barbell >= 2 * $plate) {
                if (!isset($plates_needed[strval($plate)])) {
                    $plates_needed[strval($plate)] = 0;
                }
                $plates_needed[strval($plate)]++;
                $weight_without_barbell -= 2 * $plate; // For both sides
            }
        }
    

        $results['plates_needed'] = $plates_needed;
        return $results;
    }

    $weight_in_lbs = floatval($_POST['weight_in_lbs']);
    $_SESSION['results'] = platesNeeded($weight_in_lbs);
    
    // Redirect back to the form page
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styling.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Conversion & Denomination</title>
</head>
<body>
    <form action="plates_calculator.php" method="post">
        Enter weight in lbs: <input type="number" step="0.01" name="weight_in_lbs" autofocus>
        <input type="submit" value="Calculate">
    </form>

    <?php
    if (isset($_SESSION['results'])) {
        $results = $_SESSION['results'];

        echo "<h3>Given Weight(lbs): " . $results['weight_lbs'] . "lbs</h3>";
        echo "<h3>Target weight in kg: " . $results['weight_kg'] . "kg</h3>";

        echo "<h4>Plates needed on each side:</h4>";
        echo "<ul>";
        foreach($results['plates_needed'] as $plate => $count) {
            $class_name = 'plate-' . str_replace('.', '_', $plate);
            echo "<li class='" . $class_name . "'>" . floatval($plate) . " kg plates: $count</li>";
        }
        echo "</ul>";

        // Clear the session data
        unset($_SESSION['results']);
    }
    ?>
</body>
</html>
