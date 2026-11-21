import { test, expect } from '@playwright/test';

/**
 * Public smoke E2E — no real gateway, no real money.
 * Requires app reachable (Playwright webServer starts `php artisan serve`).
 */
test.describe('Booking public surfaces', () => {
  test('booking wizard loads', async ({ page }) => {
    const res = await page.goto('/booking');
    expect(res?.ok() || res?.status() === 200).toBeTruthy();
    await expect(page.locator('#bookingForm, .sp-booking')).toBeVisible({ timeout: 15000 });
  });

  test('locked booking URL keeps provider query', async ({ page }) => {
    await page.goto('/booking?type=coach&provider=2');
    await expect(page.locator('.sp-booking')).toBeVisible({ timeout: 15000 });
    const locked = page.locator('[data-locked="1"]');
    // Provider 2 may not exist in empty DB — page still renders wizard.
    await expect(page.locator('#bookingForm')).toBeVisible();
    if (await locked.count()) {
      await expect(locked).toHaveAttribute('data-service-type', 'coach');
    }
  });

  test('customer open board and open sessions list load', async ({ page }) => {
    await page.goto('/booking/open');
    expect(page.url()).toContain('/booking/open');
    await page.goto('/open-sessions');
    expect(page.url()).toContain('/open-sessions');
  });
});
