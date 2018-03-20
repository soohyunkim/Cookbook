$(document).ready(function() {
  $("div.cookbook-tab-menu>div.list-group>a").click(function(e) {
      e.preventDefault();
      $(this).siblings('a.active').removeClass("active");
      $(this).addClass("active");
      var index = $(this).index();
      $("div.cookbook-tab>div.cookbook-tab-content").removeClass("active");
      $("div.cookbook-tab>div.cookbook-tab-content").eq(index).addClass("active");
  });
});

