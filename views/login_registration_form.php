<form action="index.php?page=login&action=store" method="post" class="row g-3">
    <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
    <div class="col-md-6"><input type="text" name="surname" class="form-control" placeholder="Surname" required></div>
    <div class="col-md-6"><input type="date" name="dob" class="form-control"></div>
    <div class="col-md-6">
        <select name="gender" class="form-select">
            <option>M</option><option>F</option><option>Altro</option>
        </select>
    </div>
    <div class="col-12"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
    <div class="col-12"><input type="text" name="address" class="form-control" placeholder="Address" required></div>
    <div class="col-12"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
    <div class="col-12 d-grid"><input type="submit" value="SUBMIT" class="btn btn-success"></div>
</form>