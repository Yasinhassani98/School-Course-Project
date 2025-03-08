@extends('layout.base')
@section('title', 'Edit Student')
@section('content')
    
    <div class="card mb-4 mt-2 container">
        <div class="card-body p-4">
            <h5 class="card-title">Edit Student {{ $student->name }}</h5>
            <form enctype="multipart/form-data" class="row" action="{{ route('admin.students.update',$student->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="mb-3 col-md-6">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                        name="image" value="{{ old('image',$student->image) }}">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Student Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name',$student->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="gender" class="form-label">Gender<span class="text-danger">*</span></label>
                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                        <option value="male" @selected(old('gender',$student->gender) == 'male')>Male</option>
                        <option value="female" @selected(old('gender',$student->gender) == 'female')>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="classroom_id" class="form-label">Classroom</label>
                    <select class="form-control @error('classroom_id') is-invalid @enderror" id="classroom_id" name="classroom_id">
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(old('classroom_id',$student->classroom_id) == $classroom->id)>{{ $classroom->name }}</option>
                        @endforeach
                        @error('classroom_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="Email" class="form-label">Email<span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="Email"
                        name="email" value="{{ $student->user->email }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="Phone" class="form-label">Phone</label>
                    <input type="text" class="form-control @error('Phone') is-invalid @enderror" id="Phone"
                        name="Phone" value="{{ old('Phone',$student->Phone) }}" >
                    @error('Phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="enrollment_date" class="form-label">Enrollment date</label>
                    <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror"
                        id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date',$student->enrollment_date) }}" required>
                    @error('enrollment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                        name="address" value="{{ old('address',$student->address) }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="date_of_birth" class="form-label">Date of birth</label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                        id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth',$student->date_of_birth) }}" required>
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="parint_id" class="form-label">Parent</label>
                    <select class="form-control @error('parint_id') is-invalid @enderror" id="parint_id"
                        name="parint_id">
                        @foreach ($parints as $parint)
                            <option value="{{ $parint->id }}" @selected(old('parint_id',$student->parint_id) == $parint->id)>{{ $parint->name }}
                            </option>
                        @endforeach
                        @error('parint_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="active" @selected(old('status',$student->status) == 'active')>Active</option>
                        <option value="inactive" @selected(old('status',$student->status) == 'inactive')>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </div>
            </form>
        </div>
    </div>
@endsection
