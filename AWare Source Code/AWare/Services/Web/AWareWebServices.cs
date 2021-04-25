using AWare.Models.Web;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Threading.Tasks;

namespace AWare.Services.Web
{
    public class AWareWebServices
    {
        private string Uid { get; }
        private HttpClient _httpClient;

        public AWareWebServices(string uid)
        {
            Uid = uid;
        }

        public async Task<AWareWebResponse> InitializeAWareWebServicesAsync()
        {

            if (string.IsNullOrEmpty(Uid) || 
                string.IsNullOrEmpty(Globals.ApiLink))
            {
                throw new ApplicationException("An unexpected error has occurred!");
            }

            _httpClient = new HttpClient(new HttpClientHandler()
            {
                ServerCertificateCustomValidationCallback = (sender, cert, chain, sslPolicyErrors) => true
            })
            {
                BaseAddress = new Uri(Globals.ApiLink)
            };

            var requestSettings = new HttpRequestMessage(HttpMethod.Post, "")
            {
                Content = new FormUrlEncodedContent(new Dictionary<string, string>()
                {
                    { "U-ID", Uid },
                    { "PC-Name", Environment.MachineName }
                })
            };

            var aWareResponse = await _httpClient.SendAsync(requestSettings);

            if (!aWareResponse.IsSuccessStatusCode)
            {
                throw new HttpRequestException("An error occurred in the request");
            }

            var aWareResponseString = await aWareResponse.Content.ReadAsStringAsync();
            return JsonConvert.DeserializeObject<AWareWebResponse>(aWareResponseString);

        }
    }
}
