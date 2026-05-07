<style>
  footer {
    position: relative; z-index: 1;
    border-top: 1px solid rgba(255,255,255,0.07);
    padding: 36px 52px;
    display: flex; align-items: center; justify-content: space-between;
    font-size: 13.5px; color: var(--text-light);
    background: rgba(7,9,30,0.5);
  }
  footer a { color: var(--text-light); text-decoration: none; transition: color .2s; }
  footer a:hover { color: var(--accent-gold); }
  .footer-links { display: flex; gap: 24px; }

  @media (max-width: 768px) {
    footer { flex-direction: column; gap: 16px; text-align: center; padding: 28px 18px; }
    .footer-links { flex-wrap: wrap; justify-content: center; gap: 16px; }
  }
</style>

<footer>
  <div>© ২০২৬ প্রত্যয় একাডেমি। সর্বস্বত্ব সংরক্ষিত।</div>
  <div class="footer-links">
    @if (get_settings('footer_menu_one_labels') != null)
        @foreach ( json_decode(get_settings('footer_menu_one_labels')) as $key => $value)
            <a href="{{ json_decode(App\Models\Setting::where('name', 'footer_menu_one_links')->first()->value, true)[$key] }}">
                {{ $value }}
            </a>
        @endforeach
    @endif
  </div>
</footer>
