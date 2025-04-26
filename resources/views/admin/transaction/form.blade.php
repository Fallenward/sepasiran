<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $transaction?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="transaction_id" class="form-label">{{ __('Transaction Id') }}</label>
            <input type="text" name="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" value="{{ old('transaction_id', $transaction?->transaction_id) }}" id="transaction_id" placeholder="Transaction Id">
            {!! $errors->first('transaction_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cart_ids" class="form-label">{{ __('Cart Ids') }}</label>
            <input type="text" name="cart_ids" class="form-control @error('cart_ids') is-invalid @enderror" value="{{ old('cart_ids', $transaction?->cart_ids) }}" id="cart_ids" placeholder="Cart Ids">
            {!! $errors->first('cart_ids', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $transaction?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="card_number" class="form-label">{{ __('Card Number') }}</label>
            <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror" value="{{ old('card_number', $transaction?->card_number) }}" id="card_number" placeholder="Card Number">
            {!! $errors->first('card_number', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ref_id" class="form-label">{{ __('Ref Id') }}</label>
            <input type="text" name="ref_id" class="form-control @error('ref_id') is-invalid @enderror" value="{{ old('ref_id', $transaction?->ref_id) }}" id="ref_id" placeholder="Ref Id">
            {!! $errors->first('ref_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $transaction?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $transaction?->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cart_price" class="form-label">{{ __('Cart Price') }}</label>
            <input type="text" name="cart_price" class="form-control @error('cart_price') is-invalid @enderror" value="{{ old('cart_price', $transaction?->cart_price) }}" id="cart_price" placeholder="Cart Price">
            {!! $errors->first('cart_price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tax" class="form-label">{{ __('Tax') }}</label>
            <input type="text" name="tax" class="form-control @error('tax') is-invalid @enderror" value="{{ old('tax', $transaction?->tax) }}" id="tax" placeholder="Tax">
            {!! $errors->first('tax', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="discount" class="form-label">{{ __('Discount') }}</label>
            <input type="text" name="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $transaction?->discount) }}" id="discount" placeholder="Discount">
            {!! $errors->first('discount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="payment_method" class="form-label">{{ __('Payment Method') }}</label>
            <input type="text" name="payment_method" class="form-control @error('payment_method') is-invalid @enderror" value="{{ old('payment_method', $transaction?->payment_method) }}" id="payment_method" placeholder="Payment Method">
            {!! $errors->first('payment_method', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>