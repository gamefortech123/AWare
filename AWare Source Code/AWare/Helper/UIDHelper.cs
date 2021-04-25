using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AWare.Helper
{
    public class UidHelper
    {

        public string Id { get; }

        public UidHelper()
        {
            Id = GetMachineGuid();
        }

        private static string GetMachineGuid()
        {
            const string location = @"SOFTWARE\Microsoft\Cryptography";
            const string name = "MachineGuid";

            using var localMachineX64View =
                RegistryKey.OpenBaseKey(RegistryHive.LocalMachine, RegistryView.Registry64);

            using var rk = localMachineX64View.OpenSubKey(location);

            if (rk == null)
                throw new KeyNotFoundException(
                    $"Key Not Found: {location}");

            var machineGuid = rk.GetValue(name);
            if (machineGuid == null)
                throw new IndexOutOfRangeException(
                    $"Index Not Found: {name}");

            return machineGuid.ToString();
        }
    }
}
