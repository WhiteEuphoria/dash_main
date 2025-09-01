<?php
namespace App\Models;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'verification_status', 'main_balance', 'currency',
    ];
    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = ['password', 'remember_token'];
    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    /**
     * Determine if the user can access the given panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->is_admin;
        }
        if ($panel->getId() === 'client') {
            return !$this->is_admin;
        }
        return false;
    }
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
    public function hasActiveAccount(): bool
    {
        return $this->accounts()->where('status', 'Active')->exists();
    }
    public function isFullyEnabled(): bool
    {
        $status = strtolower((string) $this->verification_status);
        $isApproved = in_array($status, ['approved', 'active'], true);
        return $isApproved || $this->hasActiveAccount();
    }
    public function brokerageAccounts(): HasMany
    {
        return $this->hasMany(Account::class)->whereIn('type', ['Брокерский', 'Brokerage']);
    }
    public function transitAccounts(): HasMany
    {
        // Support both legacy Russian and English stored values
        return $this->hasMany(Account::class)->whereIn('type', ['Transit', 'Транзитный']);
    }
    public function fraudClaims(): HasMany
    {
        return $this->hasMany(FraudClaim::class);
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }
    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    protected static function booted(): void
    {
        static::updated(function (User $user): void {
            if ($user->wasChanged('currency')) {
                // Keep accounts in sync with user's currency choice
                $user->accounts()->update(['currency' => $user->currency]);

                // Also keep transactions in sync for consistent display across admin/client
                if (method_exists($user, 'transactions')) {
                    $user->transactions()->update(['currency' => $user->currency]);
                }
            }
        });
    }
}
