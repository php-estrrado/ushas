<div class="tab-pane attr" id="tab4">
    <div class="card-header mb-4"><div class="card-title">Attributes</div></div>
    <?php // echo '<pre>'; print_r($attributes); echo '</pre>'; ?>
    @if($prod_attributes && count($prod_attributes) > 0)
    @foreach($prod_attributes as $attr)
   
    <div class="col-lg-6 ">
        <div class="form-group">
           <label class="form-label view" for="fname">{{ $attr['name'] }}</label>
            <p class="view_value"> {{ $attr['value'] }} </p>
            <div class="clr"></div>
        </div>
    </div>
    @endforeach
    @endif
</div>
                        