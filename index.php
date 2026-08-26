<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descubra seu Signo</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Estilo próprio -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="starfield" aria-hidden="true"></div>

    <main class="d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-11 col-sm-9 col-md-7 col-lg-5">

                    <p class="eyebrow text-center mb-2">Mapa astral simplificado</p>
                    <h1 class="hero-title text-center mb-2">Descubra o seu signo</h1>
                    <p class="hero-subtitle text-center mb-4">
                        Informe sua data de nascimento e veja as principais<br class="d-none d-sm-block">
                        características do seu signo do zodíaco.
                    </p>

                    <div class="card card-signo">
                        <div class="card-body p-4 p-md-5">
                            <form action="resultado.php" method="POST" novalidate>
                                <label for="data_nascimento" class="form-label label-dourado">
                                    Data de nascimento
                                </label>
                                <input
                                    type="date"
                                    class="form-control form-control-lg input-signo"
                                    id="data_nascimento"
                                    name="data_nascimento"
                                    max="<?php echo date('Y-m-d'); ?>"
                                    required
                                >
                                <div class="form-text text-center mt-2 mb-4">
                                    Usamos apenas o dia e o mês para calcular seu signo.
                                </div>

                                <button type="submit" class="btn btn-signo w-100">
                                    Ver meu signo
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="rodape text-center mt-4">
                        Áries &middot; Touro &middot; Gêmeos &middot; Câncer &middot; Leão &middot; Virgem &middot;
                        Libra &middot; Escorpião &middot; Sagitário &middot; Capricórnio &middot; Aquário &middot; Peixes
                    </p>

                </div>
            </div>
        </div>
    </main>

</body>
</html>
