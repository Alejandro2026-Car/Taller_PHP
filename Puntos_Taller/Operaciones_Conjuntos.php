<?php
class OperacionesConjuntos
{
    private array $conjuntoA;
    private array $conjuntoB;

    public function __construct(array $a, array $b)
    {
        $this->conjuntoA = array_unique(array_map('intval', $a));
        $this->conjuntoB = array_unique(array_map('intval', $b));
    }

    public function union(): array
    {
        return array_values(array_unique(array_merge($this->conjuntoA, $this->conjuntoB)));
    }

    public function interseccion(): array
    {
        return array_values(array_intersect($this->conjuntoA, $this->conjuntoB));
    }

    public function diferenciaAB(): array
    {
        return array_values(array_diff($this->conjuntoA, $this->conjuntoB));
    }

    public function diferenciaBA(): array
    {
        return array_values(array_diff($this->conjuntoB, $this->conjuntoA));
    }
}

$resultados = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conjunto_a'], $_POST['conjunto_b'])) {
    $arrayA = explode(',', $_POST['conjunto_a']);
    $arrayB = explode(',', $_POST['conjunto_b']);

    $conjuntos = new OperacionesConjuntos($arrayA, $arrayB);

    $resultados = [
        'union' => $conjuntos->union(),
        'interseccion' => $conjuntos->interseccion(),
        'difAB' => $conjuntos->diferenciaAB(),
        'difBA' => $conjuntos->diferenciaBA()
    ];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 4 - Conjuntos</title>
    <link rel="stylesheet" href="../Vista/Punto4.css">
</head>

<body>
    <div class="capa-desenfoque">
        <h1>Operaciones con Conjuntos</h1>
    </div>
    <div class="container">
        <h2>4. Operaciones de Conjuntos (A y B)</h2>
        <form action="" method="POST">
            <div>
                <label for="conjunto_a">Conjunto A (separado por comas):</label>
                <input type="text" id="conjunto_a" name="conjunto_a" value="<?= htmlspecialchars($_POST['conjunto_a'] ?? '') ?>" required placeholder="Ej: 1, 2, 3, 4">
            </div>
            <div>
                <label for="conjunto_b">Conjunto B (separado por comas):</label>
                <input type="text" id="conjunto_b" name="conjunto_b" value="<?= htmlspecialchars($_POST['conjunto_b'] ?? '') ?>" required placeholder="Ej: 3, 4, 5, 6">
            </div>
            <div class="container">
                <button type="submit">Calcular Operaciones</button>
            </div>
            <div class="container">
                <button type="button">
                    <a href="../index.php">Regresar</a>
                </button>
            </div>
        </form>
    </div>

    <div class="container">
        <?php if ($resultados !== null): ?>
            <h3>Resultados:</h3>
            <ul class="conjuntos-lista">
                <li><strong>Unión (A ∪ B):</strong> {<?= implode(', ', $resultados['union']) ?>}</li>
                <li><strong>Intersección (A ∩ B):</strong> {<?= implode(', ', $resultados['interseccion']) ?>}</li>
                <li><strong>Diferencia (A - B):</strong> {<?= implode(', ', $resultados['difAB']) ?>}</li>
                <li><strong>Diferencia (B - A):</strong> {<?= implode(', ', $resultados['difBA']) ?>}</li>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>