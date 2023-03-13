<div
  id="search-modal"
  class="search-modal"
  tabindex="0"
>
  <form
    class="search-modal__form"
    role="search"
    method="get"
    action="<?php echo esc_url( home_url( '/' ) ); ?>"
  >
    <label
      for="search"
      class="screen-reader-text"
    >
      Search
    </label>
    <input
      class="search-modal__field textured-border"
      name="s"
      id="search"
      type="search"
      value="<?php echo get_search_query(); ?>"
      placeholder="Search..."
      required
    />
    <button
      type="submit"
      class="search-modal__submit btn"
    >
      <i class="fa-solid fa-magnifying-glass"></i>
      Search
    </button>
  </form>
  <button class="search-modal__close">
    <? get_template_part(
      'template-parts/icons/icon',
      'close',
      [
        'label' => 'Close search modal',
        'size' => '1.5em'
      ]
    ); ?>
  </button>
</div>