﻿namespace debraApp.Models
{
    public class Customer
    {
        public int CustomerID { get; set; }
        public string Name { get; set; }
        public string Email { get; set; }
        public string PhoneNumber { get; set; }

        public string NIC { get; set; }
        // Navigation property
        public ICollection<Sale> Sales { get; set; }
    }
}
