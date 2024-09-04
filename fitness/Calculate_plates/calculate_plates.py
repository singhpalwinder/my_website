def platesNeeded(coins, target_weight_with_barbell, barbell_weight):
    # Calculate the weight that needs to be made up with plates
    amount_to_add = target_weight_with_barbell - barbell_weight
    amount_to_add = round(amount_to_add, 2)  # Ensure we have precision up to two decimal places
    
    coins.sort(reverse=True)  # Sort coins in descending order
    
    plate_distribution = {}
    current_weight = 0.0
    
    for coin in coins:
        while current_weight + coin <= amount_to_add:
            if coin not in plate_distribution:
                plate_distribution[coin] = 0
            plate_distribution[coin] += 1
            current_weight += coin
    
    total_weight_with_barbell = current_weight + barbell_weight

    if total_weight_with_barbell > target_weight_with_barbell:
        return -1, {}
    
    return total_weight_with_barbell, plate_distribution

def main():
    denominations_in_kg = [25, 20, 15, 10, 5, 2.5, 1.25]
    target_weight_lbs = 315  # Example weight in pounds
    barbell_weight_lbs = 45  # Standard Olympic barbell weight in pounds

    # Convert target weight to kg
    target_weight_kg = target_weight_lbs * 0.453592
    barbell_weight_kg = barbell_weight_lbs * 0.453592

    closest_weight, plate_distribution = platesNeeded(denominations_in_kg, target_weight_kg, barbell_weight_kg)

    if closest_weight == -1:
        print("It is not possible to achieve the exact target weight with the available plates.")
    else:
        print(f"Total weight (including barbell): {closest_weight:.2f}kg")
        print("Plates needed on each side:")
        for key, value in plate_distribution.items():
            print(f"\t{key}kg: {value} plates")

if __name__ == "__main__":
    main()