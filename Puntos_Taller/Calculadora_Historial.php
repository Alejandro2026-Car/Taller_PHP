<?php
session_start();

class Calculadora
{
    public static function calcular(float $n1, float $n2, string $op): float
    {
        return match ($op) {
            '+' => $n1 + $n2,
            '-' => $n1 - $n2,
            '*' => $n1 * $n2,
            '/' => $n2 != 0 ? $n1 / $n2 : throw new Exception("División por cero"),
            '%' => ($n1 * $n2) / 100,
            default => 0.0,
        };
    }
}

class Historial
{
    public static function agregar(string $registro): void
    {
        if (!isset($_SESSION['historial'])) {
            $_SESSION['historial'] = [];
        }
        $_SESSION['historial'][] = $registro;
    }

    public static function obtener(): array
    {
        return $_SESSION['historial'] ?? [];
    }

    public static function borrar(): void
    {
        $_SESSION['historial'] = [];
    }
}

$resultado = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'borrar_historial') {
        Historial::borrar();
    } elseif ($accion === 'calcular') {
        $num1 = (float)($_POST['num1'] ?? 0);
        $num2 = (float)($_POST['num2'] ?? 0);
        $op = $_POST['operacion'] ?? '+';

        try {
            $resultado = Calculadora::calcular($num1, $num2, $op);
            $registro = "$num1 $op $num2 = $resultado";
            Historial::agregar($registro);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$listaHistorial = Historial::obtener();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 7 - Calculadora con Historial</title>
    <link rel="stylesheet" href="../Vista/Punto7.css">
</head>

<body>
    <div class="capa-desenfoque">
        <h1>Calculadora con Historial</h1>
    </div>
    <div class="container">
        <h2>7. Calculadora con Historial</h2>
        <form action="" method="POST">
            <div class="calc-inputs">
                <div class="form-group">
                    <label for="num1">Número 1:</label>
                    <input type="number" step="any" id="num1" name="num1" value="<?= htmlspecialchars($_POST['num1'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="operacion">Operación:</label>
                    <select id="operacion" name="operacion">
                        <option value="+" <?= (($_POST['operacion'] ?? '') === '+') ? 'selected' : '' ?>>Suma (+)</option>
                        <option value="-" <?= (($_POST['operacion'] ?? '') === '-') ? 'selected' : '' ?>>Resta (-)</option>
                        <option value="*" <?= (($_POST['operacion'] ?? '') === '*') ? 'selected' : '' ?>>Multiplicación (*)</option>
                        <option value="/" <?= (($_POST['operacion'] ?? '') === '/') ? 'selected' : '' ?>>División (/)</option>
                        <option value="%" <?= (($_POST['operacion'] ?? '') === '%') ? 'selected' : '' ?>>Porcentaje (%)</option>
                    </select>
                </div>
                <div class="form-group>
                    <label for="num2">Número 2:</label>
                    <input type="number" step="any" id="num2" name="num2" value="<?= htmlspecialchars($_POST['num2'] ?? '') ?>" required>
                </div>
            </div>
            <div class="container">
                <div class="btn-group">
                    <div class="container">
                        <button type="submit" name="accion" value="calcular" class="btn-calcular">Calcular</button>
                    </div>
                    <div class="container">
                        <button type="submit" name="accion" value="borrar_historial" class="btn-borrar">Borrar Historial</button>
                    </div>
                </div>
            </div>
            <div class="container">
                <button type="button">
                    <a href="../index.php">Regresar</a>
                </button>
            </div>
        </form>

    </div>
    <div class="container">
        <div class="resultado-box">
            <?php if ($resultado !== null): ?>
                <h3>Resultado Actual:</h3>
                <p><strong><?= $resultado ?></strong></p>
            <?php endif; ?>

            <?php if ($error): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <h3>Historial de Operaciones</h3>
            <ul class="historial-lista">
                <?php if (!empty($listaHistorial)): ?>
                    <?php foreach ($listaHistorial as $item): ?>
                        <li><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>El historial está vacío.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>

</html>