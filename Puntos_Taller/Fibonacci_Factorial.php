<?php
class CalculadoraSecuencias
{
    public static function fibonacci(int $n): array
    {
        if ($n <= 0) return [];
        if ($n === 1) return [0];

        $serie = [0, 1];
        for ($i = 2; $i < $n; $i++) {
            $serie[] = $serie[$i - 1] + $serie[$i - 2];
        }
        return $serie;
    }

    public static function factorial(int $n): array
    {
        if ($n < 0) return [];
        $resultado = 1;
        $pasos = [1];

        for ($i = 1; $i <= $n; $i++) {
            $resultado *= $i;
            $pasos[] = $resultado;
        }
        return $pasos;
    }
}

$resultado = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero'])) {
    $num = (int)$_POST['numero'];
    $op = $_POST['operacion'] ?? 'fibonacci';

    $resultado = ($op === 'fibonacci')
        ? CalculadoraSecuencias::fibonacci($num)
        : CalculadoraSecuencias::factorial($num);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 2 - Fibonacci y Factorial</title>
    <link rel="stylesheet" href="../Vista/Punto2.css">
</head>

<body>
    <div class="form-group">
        <h2>2. Sucesión de Fibonacci o Factorial</h2>
        <form action="" method="POST">
            <label for="numero">Ingrese un número:</label>
            <input type="number" id="numero" name="numero" min="0" value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>" required>

            <label for="operacion">Seleccione la operación:</label>
            <select id="operacion" name="operacion">
                <option value="fibonacci" <?= (($_POST['operacion'] ?? '') === 'fibonacci') ? 'selected' : '' ?>>Fibonacci</option>
                <option value="factorial" <?= (($_POST['operacion'] ?? '') === 'factorial') ? 'selected' : '' ?>>Factorial</option>
            </select>

            <button type="submit">Calcular</button>
            <a href="../index.php">Regresar</a>
        </form>
    </div>
    <div class="resultado-box">
        <?php if ($resultado !== null): ?>
            <h3>Resultado:</h3>
            <p><?= implode(' → ', $resultado) ?></p>
        <?php endif; ?>
        
    </div>
</body>

</html>