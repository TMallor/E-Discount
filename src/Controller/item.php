<?php
    class Item {
    public $brand;
    public $modele;
    private $price;

    public $image;

    public function __construct($brand, $modele, $price, $image) {
        $this->brand = $brand;
        $this->modele = $modele;
        $this->price = $price;
        $this->image = $image;
    }

    public function ShowDetails() {
        echo "Marque: " . $this->brand . "<br>";
        echo "Modèle: " . $this->modele . "<br>";
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
