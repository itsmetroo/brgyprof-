@extends('layouts.apps')
@section('content')
<div class="col-sm-12 text-left ">
    <h1 class="border-bottom border-bot pt-3">View Certificate Request</h1>
</div>

<div class="main-wrapper col-sm-12 text-left h-100 pr-0 pl-0">
    <div class="col-sm-12 pl-0 pr-0 search-bars">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Certificate Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $certificate->name }}</p>
                                <p><strong>Request Type:</strong> {{ $certificate->request_type }}</p>
                                <p><strong>Description:</strong> {{ $certificate->description }}</p>
                                <p><strong>Age:</strong> {{ $certificate->age }}</p>
                                <p><strong>Gender:</strong> {{ $certificate->gender }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> {{ $certificate->paid }}</p>
                                <p><strong>Price:</strong> ₱{{ number_format($certificate->price, 2) }}</p>
                                <p><strong>Date Requested:</strong> {{ $certificate->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Certificate Preview</h4>
                    </div>
                    <div class="card-body">
                        <div class="certificate-preview">
                            <div class="row">
                                <div class="column-right text-right">
                                    <img src="{{ Storage::url($layout->logo_1) }}" style="width: 120px">
                                </div>
                                <div class="column-center text-center">
                                    <p style='font-size:19px;font-family: "Times New Roman, Times, serif";'>
                                        REPUBLIC OF THE PHILIPPINES<br>
                                        {{ $layout->municipality }}<br>
                                        {{ $layout->province }}<br>
                                        <b><u>{{ $layout->barangay }}</u></b>
                                    </p>
                                    <div style='font-size:22px;font-family: "Times New Roman, Times, serif";padding:0px'>
                                        <b>{{ $layout->office }}</b>
                                    </div>
                                    <div style='font-size:24px;font-family: "Times New Roman, Times, serif;padding:0px'>
                                        <u><b>{{ $certificate->certificateType->certificate_name ?? 'CERTIFICATION' }}</b></u>
                                    </div>
                                </div>
                                <div class="column-left text-left">
                                    <img src="{{ Storage::url($layout->logo_2) }}" style="width: 120px">
                                </div>
                            </div>

                            <div class="content mt-4">
                                <p style="font-size: 17px; font-family: Arial, Helvetica, sans-serif;">
                                    <b>TO WHOM IT MAY CONCERN:</b>
                                </p>
                                
                                <p style="text-indent: 50px; font-size: 17px; font-family: Arial, Helvetica, sans-serif; text-align: justify;">
                                    THIS IS TO CERTIFY that {{ $certificate->name }}, {{ $certificate->age }} years old, {{ $certificate->gender }} 
                                    and a resident of {{ $certificate->certificateType->content_1 ?? '' }}
                                </p>

                                <p style="text-indent: 50px; font-size: 17px; font-family: Arial, Helvetica, sans-serif; text-align: justify;">
                                    {{ $certificate->certificateType->content_2 ?? '' }}
                                </p>

                                <p style="font-size: 17px; font-family: Arial, Helvetica, sans-serif; text-align: justify;">
                                    <b>DONE AND ISSUED</b> this {{ now()->format('jS') }} day of {{ now()->format('F Y') }} at the {{ $certificate->certificateType->content_3 ?? '' }}
                                </p>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-7">
                                </div>
                                <div class="col-md-5 text-center">
                                    <p style="font-size: 17px; font-family: Arial, Helvetica, sans-serif;">
                                        APPROVE BY:<br>
                                        @foreach($puno as $official)
                                        <span style="text-transform: uppercase; display: inline-block;">{{ $official->name }}</span><br>
                                        <span>{{ $official->position }}</span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>

                            <footer class="mt-5">
                                <span><i>***This is a computer-generated document. No signature is required***</i></span>
                            </footer>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-4">
                    <a href="{{ route('certificate.index') }}" class="btn btn-secondary">Back</a>
                    @if(session()->has('user'))
                        <button type="button" class="btn btn-primary" onclick="window.print()">Print Certificate</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.certificate-preview {
    background: white;
    padding: 40px;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
@media print {
    .btn, .card-header {
        display: none;
    }
    .card {
        border: none;
    }
    .certificate-preview {
        box-shadow: none;
        border: none;
    }
}
</style>
@endsection 