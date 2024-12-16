<div class="row g-3">
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" class="form-control" id="name" name="name" placeholder="Họ tên"
                value="{{ old('name') }}" required pattern="^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+$"
                title="Họ tên không được chứa số hoặc ký tự đặc biệt."
                oninvalid="this.setCustomValidity('Họ tên không được chứa số hoặc ký tự đặc biệt.')"
                oninput="this.setCustomValidity('')">
            <label for="name">Họ tên</label>
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="tel" class="form-control" id="phone" name="phone" maxlength="10" placeholder="Số điện thoại"
                value="{{ old('phone') }}" required>
            <label for="phone">Số điện thoại</label>
            @error('phone')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="datetime-local" class="form-control" id="booking_date" name="booking_date"
                value="{{ old('booking_date') }}" required min="{{ date('Y-m-d\TH:i') }}">
            <label for="booking_date">Ngày giờ đặt bàn</label>
            @error('booking_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="number" class="form-control" id="number_of_people" name="number_of_people"
                placeholder="Số người" value="{{ old('number_of_people', 1) }}" min="1" max="10" required>
            <label for="number_of_people">Số người</label>
            @error('number_of_people')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-floating">
            <textarea class="form-control" placeholder="Yêu cầu đặc biệt" id="special_request"
                name="special_request" style="height: 100px">{{ old('special_request') }}</textarea>
            <label for="special_request">Yêu cầu đặc biệt</label>
            @error('special_request')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>