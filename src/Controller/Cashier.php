<?php
    class Cashier {
    public $pseudo;
    private $id;
    private $orders;

    public $shoppinglist;

    public function __construct($id, $pseudo, $price, $image) {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->price = $price;
        $this->image = $image;
    }

    public function ShowDetails() {
        echo "Marque: " . $this->id . "<br>";
        echo "Modèle: " . $this->pseudo . "<br>";
        echo "Prix: " . $this->price . "<br>";
        echo "Image: " . $this->image . "<br>";
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }
}

$maVoiture = new Item("Mike", "UpTempo", 800);
$maVoiture->afficherDetails();

    $maVoiture->setPrice(900);
echo "Nouveau prix: " . $maVoiture->getPrice();
?>
