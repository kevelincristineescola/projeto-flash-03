<?php
/**
 * criar_banco.php
 * Execute este arquivo UMA VEZ pelo navegador (ou via linha de comando com `php criar_banco.php`)
 * para criar a pasta /banco, o arquivo signos.db e popular a tabela "signos" com os 12 signos
 * do zodíaco. Depois disso, index.php e resultado.php já funcionam normalmente.
 */

$pastaBanco = __DIR__ . '/banco';
if (!is_dir($pastaBanco)) {
    mkdir($pastaBanco, 0777, true);
}

require_once __DIR__ . '/config.php';

// Cria a tabela (se ainda não existir)
$pdo->exec("
    CREATE TABLE IF NOT EXISTS signos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL UNIQUE,
        simbolo TEXT NOT NULL,
        ordem INTEGER NOT NULL,
        data_inicio TEXT NOT NULL,
        data_fim TEXT NOT NULL,
        elemento TEXT NOT NULL,
        planeta_regente TEXT NOT NULL,
        cor TEXT NOT NULL,
        pedra TEXT NOT NULL,
        caracteristicas TEXT NOT NULL,
        pontos_fortes TEXT NOT NULL,
        pontos_fracos TEXT NOT NULL,
        compativel_com TEXT NOT NULL
    )
");

// Limpa a tabela antes de repopular, para evitar duplicatas ao rodar o script mais de uma vez
$pdo->exec("DELETE FROM signos");

$signos = [
    ['Áries', '♈', 1, '21/03', '19/04', 'Fogo', 'Marte', '#D1495B', 'Diamante',
        'Impulsivo, corajoso e cheio de energia para começar coisas novas.',
        'Iniciativa,Coragem,Liderança', 'Impaciência,Teimosia,Impulsividade',
        'Leão,Sagitário,Gêmeos'],
    ['Touro', '♉', 2, '20/04', '20/05', 'Terra', 'Vênus', '#6B8F71', 'Esmeralda',
        'Determinado, prático e apegado ao conforto e à estabilidade.',
        'Paciência,Lealdade,Persistência', 'Teimosia,Possessividade,Resistência a mudanças',
        'Virgem,Capricórnio,Câncer'],
    ['Gêmeos', '♊', 3, '21/05', '20/06', 'Ar', 'Mercúrio', '#7FA8C9', 'Ágata',
        'Comunicativo, curioso e adaptável, gosta de aprender coisas novas.',
        'Versatilidade,Comunicação,Curiosidade', 'Inconstância,Dispersão,Ansiedade',
        'Libra,Aquário,Áries'],
    ['Câncer', '♋', 4, '21/06', '22/07', 'Água', 'Lua', '#4A6FA5', 'Pérola',
        'Sensível, protetor e muito ligado à família e às emoções.',
        'Empatia,Lealdade,Intuição', 'Insegurança,Mau humor,Apego ao passado',
        'Escorpião,Peixes,Touro'],
    ['Leão', '♌', 5, '23/07', '22/08', 'Fogo', 'Sol', '#D1495B', 'Rubi',
        'Confiante, generoso e gosta de estar em evidência.',
        'Liderança,Generosidade,Criatividade', 'Orgulho,Teimosia,Vaidade',
        'Áries,Sagitário,Gêmeos'],
    ['Virgem', '♍', 6, '23/08', '22/09', 'Terra', 'Mercúrio', '#6B8F71', 'Jaspe',
        'Analítico, organizado e perfeccionista em tudo que faz.',
        'Organização,Praticidade,Atenção aos detalhes', 'Crítica excessiva,Ansiedade,Perfeccionismo',
        'Touro,Capricórnio,Câncer'],
    ['Libra', '♎', 7, '23/09', '22/10', 'Ar', 'Vênus', '#7FA8C9', 'Opala',
        'Diplomático, sociável e busca sempre o equilíbrio e a harmonia.',
        'Diplomacia,Senso de justiça,Charme', 'Indecisão,Dependência da opinião alheia,Evita conflitos',
        'Gêmeos,Aquário,Leão'],
    ['Escorpião', '♏', 8, '23/10', '21/11', 'Água', 'Plutão', '#4A6FA5', 'Topázio',
        'Intenso, misterioso e extremamente determinado.',
        'Determinação,Lealdade,Profundidade emocional', 'Ciúme,Desconfiança,Intensidade excessiva',
        'Câncer,Peixes,Virgem'],
    ['Sagitário', '♐', 9, '22/11', '21/12', 'Fogo', 'Júpiter', '#D1495B', 'Turquesa',
        'Aventureiro, otimista e apaixonado por liberdade e novos horizontes.',
        'Otimismo,Sinceridade,Espírito aventureiro', 'Impaciência,Falta de tato,Inconstância',
        'Áries,Leão,Libra'],
    ['Capricórnio', '♑', 10, '22/12', '19/01', 'Terra', 'Saturno', '#6B8F71', 'Granada',
        'Disciplinado, ambicioso e muito responsável com seus objetivos.',
        'Disciplina,Responsabilidade,Ambição', 'Rigidez,Pessimismo,Frieza aparente',
        'Touro,Virgem,Peixes'],
    ['Aquário', '♒', 11, '20/01', '18/02', 'Ar', 'Urano', '#7FA8C9', 'Ametista',
        'Independente, inovador e defensor de causas e ideias diferentes.',
        'Originalidade,Independência,Visão de futuro', 'Distanciamento emocional,Teimosia,Imprevisibilidade',
        'Gêmeos,Libra,Sagitário'],
    ['Peixes', '♓', 12, '19/02', '20/03', 'Água', 'Netuno', '#4A6FA5', 'Água-marinha',
        'Sonhador, sensível e muito ligado à intuição e à imaginação.',
        'Empatia,Criatividade,Intuição', 'Fuga da realidade,Insegurança,Deixar-se influenciar',
        'Câncer,Escorpião,Capricórnio'],
];

$sql = "INSERT INTO signos
    (nome, simbolo, ordem, data_inicio, data_fim, elemento, planeta_regente, cor, pedra, caracteristicas, pontos_fortes, pontos_fracos, compativel_com)
    VALUES (:nome, :simbolo, :ordem, :data_inicio, :data_fim, :elemento, :planeta_regente, :cor, :pedra, :caracteristicas, :pontos_fortes, :pontos_fracos, :compativel_com)";

$stmt = $pdo->prepare($sql);

foreach ($signos as $s) {
    $stmt->execute([
        ':nome' => $s[0],
        ':simbolo' => $s[1],
        ':ordem' => $s[2],
        ':data_inicio' => $s[3],
        ':data_fim' => $s[4],
        ':elemento' => $s[5],
        ':planeta_regente' => $s[6],
        ':cor' => $s[7],
        ':pedra' => $s[8],
        ':caracteristicas' => $s[9],
        ':pontos_fortes' => $s[10],
        ':pontos_fracos' => $s[11],
        ':compativel_com' => $s[12],
    ]);
}

echo "<h2 style='font-family:sans-serif'>Banco de dados criado com sucesso!</h2>";
echo "<p style='font-family:sans-serif'>Os 12 signos foram cadastrados em <code>banco/signos.db</code>.</p>";
echo "<p style='font-family:sans-serif'><a href='index.php'>Ir para a página inicial &rarr;</a></p>";
