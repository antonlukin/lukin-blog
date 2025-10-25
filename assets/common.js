(function () {
  /**
   * Create bounce loader
   */
  function createLoader(thumbnail) {
    var loader = document.createElement('div');
    loader.classList.add('card-thumbnail-loader');
    thumbnail.appendChild(loader);

    var bounce = document.createElement('span');
    bounce.classList.add('card-thumbnail-bounce');
    loader.appendChild(bounce);

    return loader;
  }

  /**
   * Click listeners for video
   */
  const posts = document.querySelectorAll('.card-thumbnail-video[data-src]');

  posts.forEach(function(video) {
    video.addEventListener('click', function (e) {
      e.preventDefault();

      var thumbnail = video.parentNode;

      while (thumbnail.firstChild ) {
        thumbnail.removeChild(thumbnail.lastChild);
      }

      var iframe = document.createElement('iframe');
      var loader = createLoader(thumbnail);

      iframe.setAttribute('allow', 'autoplay');
      iframe.setAttribute('frameborder', '0');
      iframe.setAttribute('allowfullscreen', true);
      iframe.setAttribute('src', video.dataset.src);

      iframe.addEventListener('load', function () {
        thumbnail.removeChild(loader);
      });

      thumbnail.appendChild(iframe);
    })
  });

  /**
   * Toggle map size
   */
  var map = document.getElementById('map');

  if (map) {
    var area = map.querySelector('.area');

    if (!area) {
      return;
    }

    var desktopQuery = window.matchMedia('(min-width: 768px)');

    var collapseIfMobile = function () {
      if (!desktopQuery.matches) {
        map.classList.remove('map-expanded');
      }
    };

    collapseIfMobile();

    area.addEventListener('click', function () {
      if (!desktopQuery.matches) {
        return;
      }

      map.classList.toggle('map-expanded');
    });

    desktopQuery.addEventListener('change', collapseIfMobile);
  }
})();
