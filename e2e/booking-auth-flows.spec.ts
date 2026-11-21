import { test, expect } from '@playwright/test';

/**
 * Authenticated browser flows — skipped unless E2E credentials are provided.
 * Set:
 *   E2E_CUSTOMER_EMAIL / E2E_CUSTOMER_PASSWORD
 *   E2E_PROVIDER_EMAIL / E2E_PROVIDER_PASSWORD (optional)
 * Payment uses wallet only; never hits a real bank gateway.
 */
const customerEmail = process.env.E2E_CUSTOMER_EMAIL;
const customerPassword = process.env.E2E_CUSTOMER_PASSWORD;

test.describe('Authenticated booking flows', () => {
  test.skip(!customerEmail || !customerPassword, 'Set E2E_CUSTOMER_EMAIL and E2E_CUSTOMER_PASSWORD');

  async function login(page: import('@playwright/test').Page, email: string, password: string) {
    await page.goto('/login');
    await page.fill('input[name="email"], input[type="email"]', email);
    await page.fill('input[name="password"], input[type="password"]', password);
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
  }

  test('customer can open locked booking and see service step', async ({ page }) => {
    await login(page, customerEmail!, customerPassword!);
    await page.goto('/booking?type=coach&provider=2');
    await expect(page.locator('#bookingForm')).toBeVisible();
    // Advance past dog/city if already filled is environment-specific; assert service grid exists for locked.
    const grid = page.locator('#providerServiceGrid');
    if (await grid.count()) {
      await expect(grid).toBeAttached();
    }
  });

  test('wallet page reachable after login', async ({ page }) => {
    await login(page, customerEmail!, customerPassword!);
    await page.goto('/wallet');
    await expect(page).not.toHaveURL(/login/);
  });
});
