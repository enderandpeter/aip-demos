@prepend('scripts')
	<script src="/js/jack-the-giant-player/TemplateData/UnityProgress.js"></script>
    <script src="/js/jack-the-giant-player/Build/UnityLoader.js"></script>
    <script>
      var gameInstance = UnityLoader.instantiate(
          "gameContainer",
          "/js/jack-the-giant-player/Build/JackTheGiantWeb.json",
          {onProgress: UnityProgress}
      );
    </script>
@endprepend