from playwright.sync_api import sync_playwright, expect
import time

def main():
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()
        # Viewport for mobile
        page.set_viewport_size({"width": 375, "height": 3000})

        # Need to login to view settings page!
        page.goto('http://localhost:8000/login')

        # We need default user info, let's login
        # Usually standard laravel seeders use some admin credentials
        page.fill('input[type="email"]', 'admin@masjid.com')
        page.fill('input[type="password"]', 'password')
        page.click('button:has-text("Masuk Dashboard")')

        page.wait_for_load_state('networkidle')
        time.sleep(1)
        page.goto('http://localhost:8000/settings')
        page.wait_for_load_state('networkidle')
        time.sleep(1)

        page.screenshot(path='mobile_settings.png', full_page=True)

        browser.close()

if __name__ == '__main__':
    main()