<?php
/**
 * resultado.php
 * Recebe a data de nascimento via POST, calcula o signo correspondente
 * e busca as informações completas no banco SQLite (banco/signos.db).
 */
require_once __DIR__ . '/config.php';

/**
 * Retorna o nome do signo (igual ao cadastrado no banco) a partir do dia e mês.
 */
function determinarSigno(int $dia, int $mes): string
{
    $faixas = [
        // [mes_inicio, dia_inicio, mes_fim, dia_fim, nome]
        [3, 21, 4, 19, 'Áries'],
        [4, 20, 5, 20, 'Touro'],
        [5, 21, 6, 20, 'Gêmeos'],
        [6, 21, 7, 22, 'Câncer'],
        [7, 23, 8, 22, 'Leão'],
        [8, 23, 9, 22, 'Virgem'],
        [9, 23, 10, 22, 'Libra'],
        [10, 23, 11, 21, 'Escorpião'],
        [11, 22, 12, 21, 'Sagitário'],
    ];

    foreach ($faixas as [$mIni, $dIni, $mFim, $dFim, $nome]) {
        if ($mes === $mIni && $dia >= $dIni) return $nome;
        if ($mes === $mFim && $dia <= $dFim) return $nome;
        if ($mes > $mIni && $mes < $mFim) return $nome;
    }

    // Capricórnio (22/12 a 19/01) atravessa o fim do ano
    if (($mes === 12 && $dia >= 22) || ($mes === 1 && $dia <= 19)) return 'Capricórnio';
    // Aquário (20/01 a 18/02)
    if (($mes === 1 && $dia >= 20) || ($mes === 2 && $dia <= 18)) return 'Aquário';
    // Peixes (19/02 a 20/03)
    if (($mes === 2 && $dia >= 19) || ($mes === 3 && $dia <= 20)) return 'Peixes';

    // fallback (não deve ocorrer com datas válidas)
    return 'Áries';
}

/**
 * Gera o "d" de um <path> em formato de fatia de pizza (wedge), usado na roda do zodíaco.
 */
function fatiaSvg(float $cx, float $cy, float $raio, float $anguloInicio, float $anguloFim): string
{
    $x1 = $cx + $raio * cos(deg2rad($anguloInicio));
    $y1 = $cy + $raio * sin(deg2rad($anguloInicio));
    $x2 = $cx + $raio * cos(deg2rad($anguloFim));
    $y2 = $cy + $raio * sin(deg2rad($anguloFim));
    $largeArc = ($anguloFim - $anguloInicio) > 180 ? 1 : 0;
    return "M{$cx},{$cy} L{$x1},{$y1} A{$raio},{$raio} 0 {$largeArc} 1 {$x2},{$y2} Z";
}

$erro = null;
$signo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['data_nascimento'])) {
    $dataStr = $_POST['data_nascimento'];
    $data = DateTime::createFromFormat('Y-m-d', $dataStr);

    if (!$data) {
        $erro = 'Data inválida. Volte e tente novamente.';
    } else {
        $dia = (int) $data->format('j');
        $mes = (int) $data->format('n');
        $nomeSigno = determinarSigno($dia, $mes);

        $stmt = $pdo->prepare('SELECT * FROM signos WHERE nome = :nome LIMIT 1');
        $stmt->execute([':nome' => $nomeSigno]);
        $signo = $stmt->fetch();

        if (!$signo) {
            $erro = 'Não encontramos as informações desse signo no banco de dados. '
                  . 'Execute o arquivo criar_banco.php primeiro.';
        }
    }
} else {
    $erro = 'Nenhuma data de nascimento foi enviada. Volte para a página inicial e preencha o formulário.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $signo ? 'Você é de ' . htmlspecialchars($signo['nome']) : 'Resultado'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="starfield" aria-hidden="true"></div>

    <main class="py-5">
        <div class="container">

            <?php if ($erro): ?>

                <div class="row justify-content-center">
                    <div class="col-11 col-sm-8 col-md-6">
                        <div class="alert alert-erro text-center p-4">
                            <p class="mb-3"><?php echo htmlspecialchars($erro); ?></p>
                            <a href="index.php" class="btn btn-signo">Voltar</a>
                        </div>
                    </div>
                </div>

            <?php else: ?>

                <div class="row justify-content-center align-items-center g-5">

                    <!-- Roda do zodíaco -->
                    <div class="col-11 col-sm-8 col-md-5 text-center">
                        <?php
                        $ordemAtual = (int) $signo['ordem'];
                        $centro = 210;
                        $raioExterno = 190;
                        $raioInterno = 120;
                        ?>
                        <svg viewBox="0 0 420 420" class="roda-zodiaco" role="img"
                             aria-label="Roda do zodíaco destacando <?php echo htmlspecialchars($signo['nome']); ?>">
                            <circle cx="<?php echo $centro; ?>" cy="<?php echo $centro; ?>" r="<?php echo $raioExterno + 6; ?>" class="roda-borda" />
                            <?php for ($i = 0; $i < 12; $i++):
                                $anguloIni = -90 + ($i * 30);
                                $anguloFim = $anguloIni + 30;
                                $ativa = ($i + 1) === $ordemAtual;
                                $anguloMeio = deg2rad($anguloIni + 15);
                                $tx = $centro + (($raioExterno + $raioInterno) / 2) * cos($anguloMeio);
                                $ty = $centro + (($raioExterno + $raioInterno) / 2) * sin($anguloMeio);
                            ?>
                                <path
                                    d="<?php echo fatiaSvg($centro, $centro, $raioExterno, $anguloIni, $anguloFim); ?>"
                                    class="<?php echo $ativa ? 'fatia fatia-ativa' : 'fatia'; ?>"
                                />
                            <?php endfor; ?>
                            <circle cx="<?php echo $centro; ?>" cy="<?php echo $centro; ?>" r="<?php echo $raioInterno - 6; ?>" class="roda-centro" />
                            <text x="<?php echo $centro; ?>" y="<?php echo $centro - 6; ?>" text-anchor="middle" class="roda-simbolo-central">
                                <?php echo htmlspecialchars($signo['simbolo']); ?>
                            </text>
                            <text x="<?php echo $centro; ?>" y="<?php echo $centro + 24; ?>" text-anchor="middle" class="roda-nome-central">
                                <?php echo htmlspecialchars($signo['nome']); ?>
                            </text>
                        </svg>
                    </div>

                    <!-- Card com as informações -->
                    <div class="col-11 col-sm-9 col-md-6">
                        <p class="eyebrow mb-2">Seu signo é</p>
                        <h1 class="hero-title mb-3"><?php echo htmlspecialchars($signo['nome']); ?></h1>
                        <p class="hero-subtitle mb-4">
                            <?php echo htmlspecialchars($signo['data_inicio']); ?> a <?php echo htmlspecialchars($signo['data_fim']); ?>
                        </p>

                        <div class="card card-signo mb-4">
                            <div class="card-body p-4">

                                <p class="descricao-signo mb-4"><?php echo htmlspecialchars($signo['caracteristicas']); ?></p>

                                <div class="row g-3 mb-4 info-grid">
                                    <div class="col-6">
                                        <span class="info-label">Elemento</span>
                                        <span class="info-valor"><?php echo htmlspecialchars($signo['elemento']); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <span class="info-label">Planeta regente</span>
                                        <span class="info-valor"><?php echo htmlspecialchars($signo['planeta_regente']); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <span class="info-label">Pedra</span>
                                        <span class="info-valor"><?php echo htmlspecialchars($signo['pedra']); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <span class="info-label">Compatível com</span>
                                        <span class="info-valor"><?php echo htmlspecialchars($signo['compativel_com']); ?></span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <span class="info-label d-block mb-2">Pontos fortes</span>
                                    <?php foreach (explode(',', $signo['pontos_fortes']) as $traco): ?>
                                        <span class="badge-traco badge-forte"><?php echo htmlspecialchars(trim($traco)); ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <div>
                                    <span class="info-label d-block mb-2">Pontos de atenção</span>
                                    <?php foreach (explode(',', $signo['pontos_fracos']) as $traco): ?>
                                        <span class="badge-traco badge-fraco"><?php echo htmlspecialchars(trim($traco)); ?></span>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </div>

                        <a href="index.php" class="btn btn-signo-outline">&larr; Consultar outra data</a>
                    </div>

                </div>

            <?php endif; ?>

        </div>
    </main>

</body>
</html>
