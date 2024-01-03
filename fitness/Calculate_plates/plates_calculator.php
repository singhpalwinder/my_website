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
    <script src="https://kit.fontawesome.com/05714a92a3.js" crossorigin="anonymous"></script>
    <title>Weight Conversion & Denomination</title>
</head>
<body>
    <div class="info">
        <h3>This tool is created to get the weight you would like to lift in lbs and give you an output that has:</h3><br>
        <ul>
            <li>The color of the plate acording to USAPL & IPF standards</li>
            <li>The number of plates needed on each side for the specific color</li>
        </ul>
        <h5>Note* The tool does take into consideration the barbell weight. So the weight you enter should include the barbell weight of 45lbs.</h5>
    </div>

    <form action="plates_calculator.php" method="post">
        <input type="number" step="0.01" id="weight_input" name="weight_in_lbs" placeholder="Enter Weight in Lbs" autofocus>
        <input type="submit" id="calculate_button" value="Calculate" disabled>
    </form>

  <?php if (!isset($_SESSION['results'])): ?>
    <div>
    <h4 class="info">Common Plate Denominations:</h4>
        <div class="common-denominations">
            <div class="common-denominations-data">
                <h5>135lbs or 61.23kg:</h5>
                <ul>
                    <li class='plate-20'>20kg plates: 1</li>
                </ul>
            </div>
            <div class="common-denominations-data">
                <h5>225lbs or 102.06kg:</h5>
                <ul>
                    <li class='plate-25'>25kg plates: 1</li>
                    <li class="plate-15">15kg plates: 1</li>
                </ul>
            </div>
            <div class="common-denominations-data">
                <h5>315lbs or 142.88kg:</h5>
                <ul>
                    <li class='plate-25'>25kg plates: 2</li>
                    <li class="plate-10">10kg plates: 1</li>
                    <li class="plate-1_25">1.25kg plates: 1</li>
                </ul>
            </div>
            <div class="common-denominations-data">
                <h5>405lbs or 183.70kg:</h5>
                <ul>
                    <li class='plate-25'>25kg plates: 3</li>
                    <li class="plate-5">5kg plates: 1</li>
                    <li class="plate-1_25">1.25kg plates: 1</li>
                </ul>
            </div>
            <div class="common-denominations-data">
                <h5>495lbs or 224.53kg:</h5>
                <ul>
                    <li class='plate-25'>25kg plates: 4</li>
                    <li class="plate-1_25">1.25kg plates: 1</li>
                </ul>
            </div>
            <div class="common-denominations-data">
                <h5>585lbs or 265.35kg:</h5>
                <ul>
                    <li class='plate-25'>25kg plates: 4</li>
                    <li class='plate-20'>20kg plates: 1</li>
                    <li class="plate-2_5">2.5kg plates: 1</li>
                </ul>
            </div>
        </div>
    </div>
  <?php endif; ?>   
    <?php
    if (isset($_SESSION['results'])) {
        $results = $_SESSION['results'];

       echo "<div class='result'>";
       echo "<h3>Given Weight: " . $results['weight_lbs'] . "lbs</h3>";
        echo "<h3>Target weight: " . $results['weight_kg'] . "kg</h3>";

        echo "<h4>Plates needed on each side:</h4>";
        echo "<ul>";
        foreach($results['plates_needed'] as $plate => $count) {
            $class_name = 'plate-' . str_replace('.', '_', $plate);
            echo "<li class='" . $class_name . "'>" . floatval($plate) . " kg plates: $count</li>";
        }
        echo "</ul>";
       echo "</div>";

        // Clear the session data
        unset($_SESSION['results']);
    }
    ?>
    <footer>
               <div class="icon">
                  <div>
                    <a href="https://www.twitter.com/_singhpalwinder"><i class="fa-brands fa-x-twitter"></i></a>
                  </div>
                  <div >
                    <a href ="https://github.com/singhpalwinder"><i class="fa-brands fa-github"></i></a>
                  </div>
                  <div >
                    <a href="https://www.linkedin.com/in/palwinder-singh-432559218"><i class="fa-brands fa-linkedin-in"></i></a>
                  </div>
                  <div >
                    <a href="https://www.instagram.com/_singhpalwinder"><i class = "fa-brands fa-instagram"></i></a>
                  </div>
                  <div>            
                    <a href="https://www.tiktok.com/@_singhpalwinder"><i class = "fa-brands fa-tiktok"></i></a>
                  </div>
               </div>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const weightInput = document.getElementById("weight_input");
        const calculateButton = document.getElementById("calculate_button");

        // Add 'active' class as soon as the page loads due to autofocus
        weightInput.classList.add("active");

        weightInput.addEventListener("input", function() {
            const weightValue = parseFloat(weightInput.value);
            calculateButton.disabled = isNaN(weightValue) || weightValue <= 0 || weightValue > 2000;
        });
    });
</script>
</body>
</html>
