<!DOCTYPE html>
<html lang="pt-BR" translate="no">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="https://incubadoraifpr.com.br/assets/helice-CVs34fCb.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vitrine Helice</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('loader.css') }}" />
  @vite(['resources/js/main.js'])
</head>

<body>
  <div id="app">
    <div id="loading-bg">
      <div class="loading-logo">
        <div>
          <img src="https://incubadoraifpr.com.br/assets/helice-CVs34fCb.png" alt="Vitrine Helice">
        </div>
      </div>
      <div class=" loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </div>
  </div>

  <script>
    const loaderColor = localStorage.getItem('vuexy-initial-loader-bg') || '#FFFFFF'
    const primaryColor = localStorage.getItem('vuexy-initial-loader-color') || '#7367F0'

    if (loaderColor)
      document.documentElement.style.setProperty('--initial-loader-bg', loaderColor)
    if (loaderColor)
      document.documentElement.style.setProperty('--initial-loader-bg', loaderColor)

    if (primaryColor)
      document.documentElement.style.setProperty('--initial-loader-color', primaryColor)
  </script>
</body>

</html>
