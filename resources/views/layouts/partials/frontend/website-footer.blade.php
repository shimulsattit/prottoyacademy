<style>
  .custom-footer {
    background: rgba(7, 9, 30, 0.75);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    color: var(--text-light);
    padding: 60px 40px 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    font-family: var(--font-primary);
  }
  .footer-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr;
    gap: 40px;
  }
  .footer-brand {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  .footer-brand img.logo {
    max-height: 45px;
    width: auto;
    object-fit: contain;
    align-self: flex-start;
  }
  .footer-socials {
    display: flex;
    gap: 12px;
    margin-top: 5px;
  }
  .footer-socials a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-light);
    transition: all 0.3s ease;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }
  .footer-socials a:hover {
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange));
    color: #07091e;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(245,197,24,0.3);
  }
  .footer-contact {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
    font-size: 13.5px;
  }
  .footer-contact-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: var(--text-light);
    line-height: 1.4;
  }
  .footer-contact-item i {
    color: var(--accent-gold);
    font-size: 16px;
    margin-top: 2px;
  }
  .footer-contact-item a {
    color: var(--text-light);
    text-decoration: none;
    transition: color 0.2s;
  }
  .footer-contact-item a:hover {
    color: var(--accent-gold);
  }
  .footer-column h3 {
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 20px;
    letter-spacing: 0.5px;
  }
  .footer-column ul {
    list-style: none;
    padding: 0 !important;
    margin: 0 !important;
    display: flex;
    flex-direction: column;
    gap: 6px !important;
  }
  .footer-column ul li {
    padding: 0 !important;
    margin: 0 !important;
    line-height: 1.2 !important;
  }
  .footer-column ul li a {
    color: var(--text-light);
    text-decoration: none;
    font-size: 13.5px;
    transition: all 0.2s ease;
    display: inline-block;
    padding: 3px 0 !important;
  }
  .footer-column ul li a:hover {
    color: var(--accent-gold);
    transform: translateX(4px);
  }
  .footer-bottom {
    max-width: 1200px;
    margin: 40px auto 0;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
  }
  .footer-copyright {
    font-size: 13px;
    color: var(--text-light);
    opacity: 0.8;
  }
  .footer-apps {
    display: flex;
    gap: 12px;
  }
  .app-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(7, 9, 30, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 8px 16px;
    border-radius: 8px;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  .app-btn:hover {
    border-color: var(--accent-gold);
    background: rgba(7, 9, 30, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245,197,24,0.15);
  }
  .app-btn:hover svg {
    fill: var(--accent-gold);
  }
  .app-btn:hover .app-btn-text span:last-child {
    color: var(--accent-gold);
  }
  .app-btn svg {
    width: 20px;
    height: 20px;
    fill: #ffffff;
    transition: fill 0.3s ease;
  }
  .app-btn-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
    text-align: left;
  }
  .app-btn-text span:first-child {
    font-size: 9px;
    color: var(--text-light);
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .app-btn-text span:last-child {
    font-size: 13px;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  @media (max-width: 992px) {
    .footer-container {
      grid-template-columns: 1fr 1fr;
      gap: 30px;
    }
    .footer-brand {
      grid-column: span 2;
    }
  }
  @media (max-width: 576px) {
    .custom-footer {
      padding: 40px 20px 20px;
    }
    .footer-container {
      grid-template-columns: 1fr;
      gap: 25px;
    }
    .footer-brand {
      grid-column: span 1;
    }
    .footer-bottom {
      flex-direction: column;
      text-align: center;
      margin-top: 30px;
      padding-top: 20px;
    }
    .footer-apps {
      justify-content: center;
      width: 100%;
    }
  }
</style>

<footer class="custom-footer">
  <div class="footer-container">
    
    <!-- Brand / Contact Column -->
    <div class="footer-brand">
      <a href="{{ route('home') }}" class="logo-box" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: #fff;">
        <div class="logo-icon" style="width: 44px; height: 44px; background: #ffffff; border-radius: 11px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 18px rgba(255,255,255,0.08); padding: 3px; flex-shrink: 0;">
          <img src="{{ asset('assets/images/logo/logo-icon.png') }}" alt="Prottoy Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px;">
        </div>
        <div class="logo-text" style="font-family: var(--font-primary); font-size: 19px; font-weight: 700; line-height: 1.25; color: #ffffff;">
          প্রত্যয় <span style="color: var(--accent-gold);">একাডেমি</span>
          <div class="logo-sub" style="font-size: 10px; font-weight: 400; color: #6b7db3; letter-spacing: 2px;">PROTTOY ACADEMY</div>
        </div>
      </a>
      
      <!-- Social Media Links -->
      <div class="footer-socials">
        @if (get_settings('system_facebook_url'))
          <a href="{{ get_settings('system_facebook_url') }}" target="_blank" title="Facebook">
            <i class="ri-facebook-fill"></i>
          </a>
        @endif
        @if (get_settings('system_instagram_url'))
          <a href="{{ get_settings('system_instagram_url') }}" target="_blank" title="Instagram">
            <i class="ri-instagram-line"></i>
          </a>
        @endif
        @if (get_settings('system_youtube_url'))
          <a href="{{ get_settings('system_youtube_url') }}" target="_blank" title="YouTube">
            <i class="ri-youtube-fill"></i>
          </a>
        @endif
      </div>

      <!-- Contact Info -->
      <div class="footer-contact">
        @if (get_settings('system_phone'))
          <div class="footer-contact-item">
            <i class="ri-phone-line"></i>
            <a href="tel:{{ get_settings('system_phone') }}">{{ get_settings('system_phone') }}</a>
          </div>
        @endif
        @if (get_settings('system_email'))
          <div class="footer-contact-item">
            <i class="ri-mail-line"></i>
            <a href="mailto:{{ get_settings('system_email') }}">{{ get_settings('system_email') }}</a>
          </div>
        @endif
        @if (get_settings('system_address'))
          <div class="footer-contact-item">
            <i class="ri-map-pin-line"></i>
            <span>{{ get_settings('system_address') }}</span>
          </div>
        @endif
      </div>
    </div>

    <!-- Link Widget One -->
    <div class="footer-column">
      <h3>{{ get_settings('footer_menu_one_label_text') ?: 'Features' }}</h3>
      <ul>
        @if (get_settings('footer_menu_one_labels') != null)
          @foreach (json_decode(get_settings('footer_menu_one_labels')) as $key => $value)
            @php
              $links = json_decode(App\Models\Setting::where('name', 'footer_menu_one_links')->first()->value ?? '[]', true);
              $link = isset($links[$key]) ? $links[$key] : '#';
            @endphp
            <li><a href="{{ $link }}">{{ $value }}</a></li>
          @endforeach
        @endif
      </ul>
    </div>

    <!-- Link Widget Two -->
    <div class="footer-column">
      <h3>{{ get_settings('footer_menu_tow_label_text') ?: 'Streams' }}</h3>
      <ul>
        @if (get_settings('footer_menu_two_labels') != null)
          @foreach (json_decode(get_settings('footer_menu_two_labels')) as $key => $value)
            @php
              $links = json_decode(App\Models\Setting::where('name', 'footer_menu_two_links')->first()->value ?? '[]', true);
              $link = isset($links[$key]) ? $links[$key] : '#';
            @endphp
            <li><a href="{{ $link }}">{{ $value }}</a></li>
          @endforeach
        @endif
      </ul>
    </div>

    <!-- Link Widget Three -->
    <div class="footer-column">
      <h3>{{ get_settings('footer_menu_three_label_text') ?: 'Company' }}</h3>
      <ul>
        @if (get_settings('footer_menu_three_labels') != null)
          @foreach (json_decode(get_settings('footer_menu_three_labels')) as $key => $value)
            @php
              $links = json_decode(App\Models\Setting::where('name', 'footer_menu_three_links')->first()->value ?? '[]', true);
              $link = isset($links[$key]) ? $links[$key] : '#';
            @endphp
            <li><a href="{{ $link }}">{{ $value }}</a></li>
          @endforeach
        @endif
      </ul>
    </div>

    <!-- Link Widget Four -->
    <div class="footer-column">
      <h3>{{ get_settings('footer_menu_four_label_text') ?: 'Legal' }}</h3>
      <ul>
        @if (get_settings('footer_menu_four_labels') != null)
          @foreach (json_decode(get_settings('footer_menu_four_labels')) as $key => $value)
            @php
              $links = json_decode(App\Models\Setting::where('name', 'footer_menu_four_links')->first()->value ?? '[]', true);
              $link = isset($links[$key]) ? $links[$key] : '#';
            @endphp
            <li><a href="{{ $link }}">{{ $value }}</a></li>
          @endforeach
        @endif
      </ul>
    </div>

  </div>

  @php
    $footer_text = get_settings('system_footer_text');
    $currentYearEn = date('Y');
    
    // Convert current year to Bengali digits
    $bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    $enDigits = ['0','1','2','3','4','5','6','7','8','9'];
    $currentYearBn = str_replace($enDigits, $bnDigits, $currentYearEn);
    
    // If the footer text is empty or contains default Lorem Ipsum placeholders, show the official copyright
    if (!$footer_text || str_contains($footer_text, 'Lorem Ipsum') || str_contains($footer_text, 'distracted by the readable')) {
      $footer_text = '© ' . $currentYearBn . ' প্রত্যয় একাডেমি। সর্বস্বত্ব সংরক্ষিত।';
    } else {
      // Otherwise replace any year in the custom footer text dynamically
      $footer_text = preg_replace('/\b(20\d{2})\b/', $currentYearEn, $footer_text);
      $footer_text = preg_replace('/(২০\d{2})/', $currentYearBn, $footer_text);
    }
  @endphp

  <div class="footer-bottom">
    <!-- Mobile Apps Badges (Left Side) -->
    <div class="footer-apps">
      <!-- Google Play -->
      <a href="#" class="app-btn" title="Get it on Google Play">
        <svg viewBox="0 0 512 512">
          <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58 33.3-60.1-60.1 60.1-60.1 58 33.3c13 7.5 23 20 23 36.8s-10 29.3-23 36.8zM325.3 277.7l60.1 60.1L104.6 499l220.7-221.3z"/>
        </svg>
        <div class="app-btn-text">
          <span>GET IT ON</span>
          <span>Google Play</span>
        </div>
      </a>
      <!-- App Store -->
      <a href="#" class="app-btn" title="Download on the App Store">
        <svg viewBox="0 0 384 512">
          <path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-48.7-22.9-84.5-22.9-46.9 0-92.4 26.5-116.6 68.7-49.9 86.7-12.8 214.2 34.8 283.7 23.3 33.6 50.4 70.5 86.7 69.1 35-1.4 48.3-22.8 90.6-22.8 42.1 0 54.3 22.8 90.6 22.1 37.1-.7 61-33.6 84.1-67.4 26.7-39.1 37.9-77 38.4-79.1-.9-.4-73.9-28.3-74.7-111.9zM252.2 84.8c15.4-18.6 25.8-44.4 23-70.2-22.1 1-48.8 14.8-64.7 33.3-13.6 15.6-25.6 41.7-22.1 67.2 24.6 2 49.8-12 63.8-30.3z"/>
        </svg>
        <div class="app-btn-text">
          <span>Download on the</span>
          <span>App Store</span>
        </div>
      </a>
    </div>

    <!-- Copyright (Right Side) -->
    <div class="footer-copyright">
      {!! $footer_text !!}
    </div>
  </div>
</footer>


