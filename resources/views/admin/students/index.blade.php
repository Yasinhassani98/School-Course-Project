@extends('layout.base')
@section('title', 'students List')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h4 class="card-title">students List</h4>
                    <form action="{{ route('admin.students.create') }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary">Add student</button>
                    </form>
                </div>
                <div class="active-member">
                    <div class="table-responsive">
                        <table class="table table-xs text-center mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Parent Name</th>
                                    <th>Classroom Name</th>
                                    <th>phone</th>
                                    <th>Enrollment Date</th>
                                    <th>Date of Birth</th>
                                    <th colspan="3">action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>{{ $student->name ?? 'null'}}</td>
                                    <td>{{ $student->gender ?? 'null'}}</td>
                                    <td>{{ $student->parent->name ?? 'null'}}</td>
                                    <td>{{ $student->classroom->name ?? 'null'}}</td>
                                    <td>{{ $student->phone ?? 'null'}}</td>
                                    <td>{{ $student->enrollment_date ?? 'null'}}</td>
                                    <td>{{ $student->date_of_birth ?? 'null'}}</td>
                                    <td>
                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            ><i class="fa-solid fa-pen-to-square text-warning"></i></a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.students.show', $student->id) }}"
                                            ><i class="fa-solid fa-eye text-success"></i></a>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn p-0 border-0 shadow-none bg-white" type="submit"><i class="fa-solid fa-trash text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">No data found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $students->links() }}
    </div>
</div>
@endsection