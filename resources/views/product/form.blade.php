<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $product?->title) }}" id="title" placeholder="Title">
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $product?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="slug" class="form-label">{{ __('Slug') }}</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product?->slug) }}" id="slug" placeholder="Slug">
            {!! $errors->first('slug', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label">{{ __('Price') }}</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product?->price) }}" id="price" placeholder="Price">
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="discount_price" class="form-label">{{ __('Discount Price') }}</label>
            <input type="text" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $product?->discount_price) }}" id="discount_price" placeholder="Discount Price">
            {!! $errors->first('discount_price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="stock" class="form-label">{{ __('Stock') }}</label>
            <input type="text" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product?->stock) }}" id="stock" placeholder="Stock">
            {!! $errors->first('stock', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_active" class="form-label">{{ __('Is Active') }}</label>
            <input type="text" name="is_active" class="form-control @error('is_active') is-invalid @enderror" value="{{ old('is_active', $product?->is_active) }}" id="is_active" placeholder="Is Active">
            {!! $errors->first('is_active', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_featured" class="form-label">{{ __('Is Featured') }}</label>
            <input type="text" name="is_featured" class="form-control @error('is_featured') is-invalid @enderror" value="{{ old('is_featured', $product?->is_featured) }}" id="is_featured" placeholder="Is Featured">
            {!! $errors->first('is_featured', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sku" class="form-label">{{ __('Sku') }}</label>
            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product?->sku) }}" id="sku" placeholder="Sku">
            {!! $errors->first('sku', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="barcode" class="form-label">{{ __('Barcode') }}</label>
            <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $product?->barcode) }}" id="barcode" placeholder="Barcode">
            {!! $errors->first('barcode', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="category_id" class="form-label">{{ __('Category Id') }}</label>
            <input type="text" name="category_id" class="form-control @error('category_id') is-invalid @enderror" value="{{ old('category_id', $product?->category_id) }}" id="category_id" placeholder="Category Id">
            {!! $errors->first('category_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="brand_id" class="form-label">{{ __('Brand Id') }}</label>
            <input type="text" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror" value="{{ old('brand_id', $product?->brand_id) }}" id="brand_id" placeholder="Brand Id">
            {!! $errors->first('brand_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="thumbnail" class="form-label">{{ __('Thumbnail') }}</label>
            <input type="text" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" value="{{ old('thumbnail', $product?->thumbnail) }}" id="thumbnail" placeholder="Thumbnail">
            {!! $errors->first('thumbnail', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="images" class="form-label">{{ __('Images') }}</label>
            <input type="text" name="images" class="form-control @error('images') is-invalid @enderror" value="{{ old('images', $product?->images) }}" id="images" placeholder="Images">
            {!! $errors->first('images', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="buy_link" class="form-label">{{ __('Buy Link') }}</label>
            <input type="text" name="buy_link" class="form-control @error('buy_link') is-invalid @enderror" value="{{ old('buy_link', $product?->buy_link) }}" id="buy_link" placeholder="Buy Link">
            {!! $errors->first('buy_link', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="created_by" class="form-label">{{ __('Created By') }}</label>
            <input type="text" name="created_by" class="form-control @error('created_by') is-invalid @enderror" value="{{ old('created_by', $product?->created_by) }}" id="created_by" placeholder="Created By">
            {!! $errors->first('created_by', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="updated_by" class="form-label">{{ __('Updated By') }}</label>
            <input type="text" name="updated_by" class="form-control @error('updated_by') is-invalid @enderror" value="{{ old('updated_by', $product?->updated_by) }}" id="updated_by" placeholder="Updated By">
            {!! $errors->first('updated_by', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>