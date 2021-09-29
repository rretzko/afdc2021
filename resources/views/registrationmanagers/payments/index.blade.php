@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    {{-- COUNTY SCOPE --}}
                    <x-navs.togglecounties toggle="{{$toggle}}" :counties="$counties" :mycounties="$mycounties"/>

                    {{-- ACTIVITY NAVIGATION MENU --}}
                    <x-navs.activities toggle="{{ $toggle }}"
                                       :counties="$counties"
                                       :mycounties="$mycounties"
                                       :eventversion="$eventversion"
                    />

                    <div>
                        <h4>Payment Form</h4>
                        <form method="post" action="">

                            @csrf

                            <div class="input_group">
                                <label>School</label>
                                <select name="school_id" onchange="getTeachers()">
                                    <option value="0">- Select School -</option>
                                @foreach($schools AS $school)
                                    <option value="{{ $school->id }}">{{ $school->shortName }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div>Fields</div>
                            <div>
                                <ul>
                                    <li>user_id</li>
                                    <li>registrant_id</li>
                                    <li>eventversion_id</li>
                                    <li>paymenttype_id</li>
                                    <li>school_id</li>
                                    <li>vendor_id</li>
                                    <li>amount</li>
                                    <li>updated_by</li>
                                </ul>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script>
        function getTeachers(){
            alert('getting teachers');
        }
    </script>
@endsection


