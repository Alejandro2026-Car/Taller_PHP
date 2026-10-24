<?php
class ConversorBase
{
    public static function decimalABinario(int $numero): string
    {
        if ($numero === 0) return '0';

        $esNegativo = $numero < 0;
        $n = abs($numero);
        $binario = '';

        while ($n > 0) {
            $binario = ($n % 2) . $binario;
            $n = intdiv($n, 2);
        }

        return $esNegativo ? '-' . $binario : $binario;
    }
}

$binario = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero'])) {
    $binario = ConversorBase::decimalABinario((int)$_POST['numero']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 5 - Decimal a Binario</title>
    <link rel="stylesheet" href="../Vista/Punto5.css">
</head>

<body>
    <div class="capa-desenfoque">
        <h1>Conversión a Binario</h1>
    </div>
    <div class="container">
        <h2>5. Convertir entero a Binario</h2>
        <form action="" method="POST">
            <label for="numero">Ingrese un número entero:</label>
            <input type="number" id="numero" name="numero" value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>" required>
            <div class="container">
                <button type="submit">Convertir a Binario</button>
            </div>
            <div class="container">
                <button type="button">
                    <a href="../index.php">Regresar</a>
                </button>
            </div>
        </form>
    </div>
    <div class="binario-output">
        <?php if ($binario !== null): ?>
            <h3>Resultado:</h3>
            <p><strong>En Binario:</strong> <?= $binario ?></p>
        <?php endif; ?>
    </div>
</body>

</html>