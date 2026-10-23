<?php
class Estadistica
{
    private array $numeros;

    public function __construct(array $numeros)
    {
        $this->numeros = array_map('floatval', $numeros);
    }

    public function promedio(): float
    {
        if (empty($this->numeros)) return 0;
        return array_sum($this->numeros) / count($this->numeros);
    }

    public function mediana(): float
    {
        if (empty($this->numeros)) return 0;
        $copia = $this->numeros;
        sort($copia);
        $count = count($copia);
        $mitad = (int)floor($count / 2);

        if ($count % 2 !== 0) {
            return $copia[$mitad];
        }
        return ($copia[$mitad - 1] + $copia[$mitad]) / 2.0;
    }

    public function moda(): array
    {
        if (empty($this->numeros)) return [];
        $frecuencias = array_count_values(array_map('strval', $this->numeros));
        $maxFrecuencia = max($frecuencias);

        $modas = [];
        foreach ($frecuencias as $num => $frec) {
            if ($frec === $maxFrecuencia) {
                $modas[] = (float)$num;
            }
        }
        return $modas;
    }
}

$promedio = $mediana = $moda = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['numeros'])) {
    $arrayNumeros = explode(',', $_POST['numeros']);
    $stats = new Estadistica($arrayNumeros);

    $promedio = $stats->promedio();
    $mediana = $stats->mediana();
    $moda = $stats->moda();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 3 - Estadística</title>
    <link rel="stylesheet" href="../Vista.css">
</head>

<body>
    <div class="Back3">
        <h2>3. Promedio, Media (Mediana) y Moda</h2>
        <form action="" method="POST">
            <br>
            <label for="numeros">Ingrese los números (separados por comas):</label>
            <br>
            <input type="text" id="numeros" name="numeros" value="<?= htmlspecialchars($_POST['numeros'] ?? '') ?>" required placeholder="Ej: 10, 5, 8, 5, 20">
            <div class="Regresar">
                <button type="submit">Calcular Estadísticas</button>
            </div>

        </form>

        <?php if ($promedio !== null): ?>
            <h3>Resultados:</h3>
            <ul>
                <li><strong>Promedio:</strong> <?= round($promedio, 2) ?></li>
                <li><strong>Mediana (Media):</strong> <?= $mediana ?></li>
                <li><strong>Moda:</strong> <?= implode(', ', $moda) ?></li>
            </ul>
        <?php endif; ?>
        <a href="../index.php">Regresar</a>
    </div>

</body>

</html>