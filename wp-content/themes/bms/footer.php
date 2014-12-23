              </div>
            </div>

          </section>
            <img class="footer_left_plit" src="<?php bloginfo('template_directory'); ?>/img/footer_left_plit.png">
            <img class="footer_right_plit" src="<?php bloginfo('template_directory'); ?>/img/footer_right_plit.png">
          <footer class="row social">
            <div class="large-12 columns">
              <img src="<?php bloginfo('template_directory'); ?>/img/facebook.jpg"> <img src="<?php bloginfo('template_directory'); ?>/img/twitter.jpg"> <img src="<?php bloginfo('template_directory'); ?>/img/linkedin.jpg">
            </div>
            
          </footer>

          <section class="footer_bottom">
            <div class="row">
              <div class="large-12 columns">
                <div class="large-8 columns">
                  <p>BMS Controls © 2013:</p>
                </div>
                  
              </div>
            </div>
          </section>

        </section>
      </div>

    </div>

    <script src="<?php bloginfo('template_directory'); ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/bower_components/foundation/js/foundation.min.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/app.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/slick.js"></script>
    <script type="text/javascript">
      $('.one-time').slick({
          dots: true,
          infinite: false,
          placeholders: false,
          speed: 300,
          slidesToShow: 3,
          touchMove: true,
          slidesToScroll: 1,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: true,
                dots: true
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
      });

    </script>
  </body>
</html>
