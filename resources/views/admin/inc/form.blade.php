


     @csrf        
        
     <div class="form-group">
         <h6><label for="FirstName">First Name</label></h6>
         <input class="form-control" type="text" name="FirstName" value="{{old('FirstName') ?? $driver->FirstName}}">
     </div>

    
     <div class="form-group">
         <h6><label for="LastName">Last Name</label></h6>
        <input class="form-control" type="text" name="LastName" value="{{old('LastName') ?? $driver->LastName}}">
     </div>
     
     <div class="form-group">
         <h6><label for="PermitNumber">permit Number</label></h6>
        <input class="form-control" type="text" name="PermitNumber" value="{{old('PermitNumber')?? $driver->PermitNumber}}">
     </div>

    
     <div class="form-group">
         <h6><label for="LicenseNumber">License Number</label></h6>
        <input class="form-control" type="text" name="LicenseNumber" value="{{old('LicenseNumber')?? $driver->LicenseNumber}}">
     </div>


     <div class="form-group">
         <h6><label for="PermitExpiry">Permit Expiry</label></h6>
         <input type="date" max="3000-12-31" min="1000-01-01" class="form-control" type="text" name="PermitExpiry" value="{{old('PermitExpiry')?? $driver->PermitExpiry}}">
     </div>

    
     <div class="form-group">
         <h6><label for="LicenseExpiry">License Expiry</label></h6>
         <input type="date" max="3000-12-31" min="1000-01-01"class="form-control" type="text" name="LicenseExpiry" value="{{old('LicenseExpiry')?? $driver->LicenseExpiry}}">
     </div>

    
     <div class="form-group">
         <h6><label for="AbstractExpiry">Abstract Expiry</label></h6>
         <input type="date" max="3000-12-31" min="1000-01-01"class="form-control" type="text" name="AbstractExpiry" value="{{old('AbstractExpiry')?? $driver->AbstractExpiry}}">
     </div>

    
     <div class="form-group">
         <h6><label for="HomePhone">Home Phone</label></h6>
        <input class="form-control" type="text" name="HomePhone" value="{{old('HomePhone')?? $driver->HomePhone}}">
     </div>

    
     <div class="form-group">
         <h6><label for="TAXI_HOST">TAXI HOST</label></h6>
        <input class="form-control" type="text" name="TAXI_HOST" value="{{old('TAXI_HOST')?? $driver->TAXI_HOST}}">
     </div>

    
     <div class="form-group">
         <h6><label for="ADDRESS">ADDRESS</label></h6>
        <input class="form-control" type="text" name="ADDRESS" value="{{old('ADDRESS')?? $driver->ADDRESS}}">
     </div>

    
     <div class="form-group">
         <h6><label for="EMAIL">EMAIL</label></h6>
        <input class="form-control" type="text" name="email" value="{{old('email')?? $driver->email}}">
     </div>

    
     <div class="form-group">
         <h6><label for="START_DATE">START DATE</label></h6>
         <input type="date" max="3000-12-31" min="1000-01-01" class="form-control" type="date" name="START_DATE" value="{{old('START_DATE')?? $driver->START_DATE}}">
     </div>

    
     <div class="form-group">
         <h6><label for="END_DATE">END DATE</label></h6>
         <input type="date" max="3000-12-31" min="1000-01-01" class="form-control" type="date" name="END_DATE" value="{{old('END_DATE')?? $driver->END_DATE}}">
     </div>

    
     <div class="form-group">
         <h6><label for="DRIVER_STATUS">DRIVER STATUS</label></h6>
     <input class="form-control" type="text" name="DRIVER_STATUS" value="{{old('DRIVER_STATUS')?? $driver->DRIVER_STATUS}}">
     </div>

     
     <div class="form-group">
         <h6><label for="LICENSE_CLASS">LICENSE CLASS</label></h6>
         <input class="form-control" type="text" name="LICENSE_CLASS" value="{{old('LICENSE_CLASS')?? $driver->LICENSE_CLASS}}">
    </div>
        
    {{-- driver image --}}
    <div class="form-group">
        <h6><label for="DRIVER_IMAGE">Driver image</label></h6>
        <input class="form-control" style ="padding-bottom: 36px" type="file" name="image" value="{{old('DRIVER_IMAGE')?? $driver->DRIVER_IMAGE}}">
    </div>
        
     <div class="form-group">
         <h6><label for="LICENSE_PATH">LICENSE file</label></h6>
        <input class="form-control" style ="padding-bottom: 36px" type="file" name="LICENSE_PATH" value="{{old('LICENSE_PATH')?? $driver->LICENSE_PATH}}">
     </div>

    
     <div class="form-group">
         <h6><label for="SP_FILE_PATH">SP file</label></h6>
        <input class="form-control" style ="padding-bottom: 36px" type="file" name="SP_FILE_PATH" value="{{old('SP_FILE_PATH')?? $driver->SP_FILE_PATH}}">
     </div>

     <div class="form-group">
         <h6><label for="PERMIT_PATH">PERMIT file</label></h6>
        <input class="form-control" style ="padding-bottom: 36px" type="file" name="PERMIT_PATH" value="{{old('PERMIT_PATH')?? $driver->PERMIT_PATH}}">
     </div>

     <div class="form-group">
         <h6><label for="TAXIHOST_PATH">TAXIHOST file</label></h6>
        <input class="form-control" style ="padding-bottom: 36px" type="file" name="TAXIHOST_PATH" value="{{old('TAXIHOST_PATH')?? $driver->TAXIHOST_PATH}}">
     </div>

     <div class="form-group">
         <h6><label for="ABSTRACT_PATH">ABSTRACT file</label></h6>
        <input class="form-control" style ="padding-bottom: 36px" type="file" name="ABSTRACT_PATH" value="{{old('ABSTRACT_PATH')?? $driver->ABSTRACT_PATH}}">
     </div>
     @if ($show_password_assigne)
        <div class="form-group">
                <h6><label for="EMAIL">Password</label></h6>
                <input id="new-password" type="password" class="form-control" name="password">
            </div>
        <div class="form-group">
                <h6><label for="EMAIL">Confirm Password</label></h6>
                <input id="new-password-confirm" type="password" class="form-control" name="password_confirmation">
        </div>
     @else
         
     @endif
