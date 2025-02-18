<?php
    class Cashier {
    public $pseudo;
    private $id;
    private $orders;

    public $shoppinglist;

    public function __construct($id, $pseudo, $orders, $image) {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->orders = $orders;
        $this->image = $image;
    }

    public function ShowDetails() {
        echo "Marque: " . $this->id . "<br>";
        echo "Modèle: " . $this->pseudo . "<br>";
        echo "Prix: " . $this->orders . "<br>";
        echo "Image: " . $this->image . "<br>";
    }

    public function getPrice() {
        return $this->orders;
    }

    public function setPrice($orders) {
        $this->orders = $orders;
    }
}

$maVoiture = new Item("Mike", "UpTempo", 800);
$maVoiture->afficherDetails();

    $maVoiture->setPrice(900);
echo "Nouveau prix: " . $maVoiture->getPrice();
?>
