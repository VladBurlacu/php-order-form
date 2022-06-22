<?php

// This file is your starting point (= since it's the index)
// It will contain most of the logic, to prevent making a messy mix in the html

// This line makes PHP behave in a more strict way
declare(strict_types=1);

// We are going to use session variables so we need to enable sessions
session_start();

// Use this function when you need to need an overview of these variables
function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

// TODO: provide some products (you may overwrite the example)
$products = [
    ['name' => 'Jupiler', 'price' => 2.5],
    ['name' => 'Cola', 'price' => 1.5],
    ['name' => 'Fanta', 'price' => 1.5],
    ['name' => 'Sprite', 'price' => 1.5],
    ['name' => 'Duvel', 'price' => 3.5],
];

$totalValue = 0;

function validate()
{
    // TODO: This function will send a list of invalid fields back
    $warnings = [];
    //error messages
    $emailValidation = ' Invalid email';
    $cityStreetValidation = ' Invalid field';
    $streetNrValidation = ' Invalid field';
    $zipValidation= ' Invalid field';

    //validate e-mail
    if (empty(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
        array_push($warnings, 'email'.$emailValidation);
    }
    //validate street
    if (empty($_POST['street'])){
        array_push($warnings, 'street'.$cityStreetValidation);
    }
    //validate streetnumber
    if (empty($_POST['streetnumber'])){
        array_push($warnings, 'street number'.$streetNrValidation);
    }

    //validate city
    if (!ctype_alpha($_POST['city'])){
        array_push($warnings, 'city'.$cityStreetValidation);
    }
    //validate zipcode
    if (empty($_POST['zipcode'])){
        array_push($warnings, 'zipcode'.$zipValidation);
    } else if (!ctype_digit($_POST['zipcode'])){
        array_push($warnings, 'zipcode'.$zipValidation);
    }
    //validate products
    if (empty($_POST['products'])){
        array_push($warnings, 'You need to choose one of our products!');
    }
    return $warnings;
}

function handleForm($products)
{
    // TODO: form related tasks (step 1)

    // Validation (step 2)
    $invalidFields = validate();
    $error = implode('<br>', $invalidFields);
    if (!empty($invalidFields)) {
        // TODO: handle errors

        echo '<div class="alert alert-danger text-center" role="alert">';
        echo $error;
        echo "</div>";

    } else {
        // TODO: handle successful submission
        echo '<div class="alert alert-success text-center" role="alert">';
        echo "You ordered following items:<br>";
        echo getOrder($products);
        echo "<br>For delivery at: <br>";
        echo getAdress();
        echo "<br>The total cost of your order is: ";
        echo calcPrice($products);
        echo "&euro;";
        echo "<br>Estimated delivery time: 1-3 workdays.";
        echo "</div>";
    }
}

function getAdress()
{

    $street = $_POST['street'];
    $streetNumber = $_POST['streetnumber'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $adress = $street . " " . $streetNumber . "<br>" . $zipcode . " " . $city;
    return $adress;
}

function getOrder($products)
{

    $order = "";
    foreach ($_POST['products'] as $selected => $item) {
        $order .= "- " . $products[$selected]['name'] . "<br>";
    }
    return $order;
}


function calcPrice($products)
{

    $total = 0;

    foreach ($_POST['products'] as $selected => $item) {
        $quantity = $_POST['quantity'][$selected];
        $total += $products[$selected]['price'] * $quantity;
    }
    return $total;
}


// TODO: replace this if by an actual check
$formSubmitted = false;
if (isset($_POST['submit'])) {
    $formSubmitted = true;
}
if ($formSubmitted) {
    handleForm($products);

}

// create session variables
$street = "";
$streetNumber = "";
$city = "";
$zipcode = "";

//first time the user visits page and fills in form, store the value's in the super global $_SESSION
if (!empty($_POST)){
    $_SESSION['street'] = $_POST['street'];
    $_SESSION['streetnumber'] = $_POST['streetnumber'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['zipcode'] = $_POST['zipcode'];
}

if (!empty($_SESSION)){
    if (!empty($_SESSION['street'])){
        $street = $_SESSION['street'];
    }
    if (!empty($_SESSION['streetnumber'])){
        $streetnumber = $_SESSION['streetnumber'];
    }
    if (!empty($_SESSION['city'])){
        $city = $_SESSION['city'];
    }
    if (!empty($_SESSION['zipcode'])){
        $zipcode = $_SESSION['zipcode'];
    }
}

require 'form-view.php';