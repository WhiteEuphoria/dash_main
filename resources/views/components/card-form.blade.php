<div class="space-y-4" x-data="cardForm()">
    <div>
        <label class="block text-sm font-medium">Card Number</label>
        <input type="text" x-model="number" x-on:input="formatNumber" class="mt-1 w-full rounded border px-3 py-2" placeholder="4111 1111 1111 1111" maxlength="19">
        <p x-show="number && !isValidLuhn(number)" class="text-red-600 text-xs mt-1">Invalid card number</p>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Expiry (MM/YY)</label>
            <input type="text" x-model="expiry" x-on:input="formatExpiry" class="mt-1 w-full rounded border px-3 py-2" placeholder="MM/YY" maxlength="5">
        </div>
        <div>
            <label class="block text-sm font-medium">CVV</label>
            <input type="password" x-model="cvv" class="mt-1 w-full rounded border px-3 py-2" placeholder="***" maxlength="4" autocomplete="off">
        </div>
    </div>
    <script>
    function cardForm(){
        return {
            number: '', expiry: '', cvv: '',
            formatNumber(e){
                this.number = this.number.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim();
            },
            formatExpiry(e){
                this.expiry = this.expiry.replace(/\D/g,'').slice(0,4);
                if(this.expiry.length >= 3){
                    this.expiry = this.expiry.slice(0,2) + '/' + this.expiry.slice(2);
                }
            },
            isValidLuhn(num){
                const s = num.replace(/\s+/g,'');
                let sum = 0, dbl = false;
                for(let i = s.length - 1; i >= 0; i--){
                    let d = parseInt(s[i],10);
                    if(dbl){ d *= 2; if(d > 9) d -= 9; }
                    sum += d; dbl = !dbl;
                }
                return (sum % 10) === 0;
            }
        }
    }
    </script>
    <!-- IMPORTANT: CVV is NOT stored server-side. Use only for gateway submission. -->
</div>
