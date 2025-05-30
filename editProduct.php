<?php // This file edits a product or service in the database.
include 'DBConnect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["item_id"]) && !isset($_POST["update_product"])) {
    $itemID = $_POST["item_id"];

    // Get the product from the database
    $sql = "SELECT * FROM products_and_services WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $itemID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the product was found
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} elseif (isset($_POST["update_product"])) {
    // Get the item_id, business_id, category_id, item_name, item_description, and item_price from the form
    $itemID = $_POST["item_id"];
    $businessID = $_POST["business_id"];
    $categoryID = $_POST["category_id"];
    $itemName = $_POST["item_name"];
    $itemDesc = $_POST["item_description"];
    $itemPrice = $_POST["item_price"];

    // Update the product in the database
    $sql = "UPDATE products_and_services SET item_name=?, description=?, price=? WHERE item_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $itemName, $itemDesc, $itemPrice, $itemID);
    $stmt->execute();

    // Redirect to the editBusiness.php page
    echo "<form id='redirectForm' method='POST' action='editBusiness.php'>
            <input type='hidden' name='business_id' value='" . htmlspecialchars($businessID) . "'>
          </form>
          <script>document.getElementById('redirectForm').submit();</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product or Service - Business Directory</title>
</head>
<body>

<!-- Display the title of the page -->
<h2>Edit a Product or Service</h2>

<!-- Form to edit the product or service -->
<form method="POST" action="editProduct.php">
    <input type="hidden" name="item_id" value="<?= htmlspecialchars($product['item_id']) ?>">
    <input type="hidden" name="business_id" value="<?= htmlspecialchars($product['business_id']) ?>">
    <input type="hidden" name="category_id" value="<?= htmlspecialchars($product['category_id']) ?>">
    <table style="width:100%">
        <tr>
            <td class="tlabel">Item Name</td>
            <td><input type="text" name="item_name" value="<?= htmlspecialchars($product['item_name']) ?>" required></td>
        </tr>
        <tr>
            <td class="tlabel">Description</td>
            <td><input type="text" name="item_description" value="<?= htmlspecialchars($product['description']) ?>" required></td>
        </tr>
        <tr>
            <td class="tlabel">Price</td>
            <td><input type="number" step="0.01" name="item_price" value="<?= htmlspecialchars($product['price']) ?>" required></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="update_product" value="Update Product"></td>
        </tr>
    </table>
</form>

</body>
</html>
